<?php

namespace Modules\Purchase\Entities;

use App\Traits\FormatTimestamps;
use App\Traits\GenerateCode;
use App\Traits\NotifiableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Inventory\Entities\InventoryParts;
use Modules\Inventory\Entities\Vendor;
use Illuminate\Support\Facades\DB;

class Purchase extends Model
{
    use FormatTimestamps;
    use GenerateCode;
    use HasFactory;
    use NotifiableModel;

    protected $fillable = [
        'code',
        'vendor_id',
        'date',
        'total',
        'status',
        'req_img_path',
        'order_path',
    ];

    /**
     * Get the purchase that owns the PurchaseDetail
     *
     * @return \Modules\Purchase\Database\factories\PurchaseFactory
     */
    protected static function newFactory()
    {
        return \Modules\Purchase\Database\factories\PurchaseFactory::new();
    }

    /**
     * Get the code.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($purchase) {
            $purchase->code = $purchase->generateCode();
        });
    }

    /**
     * Update the status and send notification
     *
     * @param string $status
     * @return void
     * @throws \Exception
     */
    public function updateStatus($status)
    {
        DB::beginTransaction();
        try {
            $oldStatus = $this->status;
            
            // If trying to change from approved to another status, check time constraint
            if ($oldStatus === 'approved' && ($status === 'pending' || $status === 'rejected')) {
                // Check if more than one hour has passed since approval
                // Since we only update the status in this method, we can use updated_at to track when status last changed
                if (now()->diffInHours($this->updated_at) >= 1) {
                    throw new \Exception('Status cannot be changed from approved after one hour has passed');
                }
            }
            
            $this->status = $status;
            $this->save();

            $this->loadMissing('details'); // Ensure details are loaded

            // Update inventory quantities when purchase is approved
            if ($status === 'approved' && $oldStatus !== 'approved') {
                // Add inventory quantities when approving
                foreach ($this->details as $detail) {
                    $part = InventoryParts::find($detail->parts_id);
                    if ($part) {
                        $part->qty += $detail->qty;
                        $part->save();
                    }
                }
            } 
            // Handle reversal from approved to pending or rejected
            elseif ($oldStatus === 'approved' && ($status === 'pending' || $status === 'rejected')) {
                // Remove inventory quantities when un-approving
                foreach ($this->details as $detail) {
                    $part = InventoryParts::find($detail->parts_id);
                    if ($part) {
                        // Ensure quantity doesn't go below zero
                        $part->qty = max(0, $part->qty - $detail->qty);
                        $part->save();
                    }
                }
            }

            if (in_array($status, ['approved', 'rejected'])) {
                $this->sendApprovalNotification();
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Get the statues.
     */
    public static function getStatues(): array
    {
        return [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];
    }

    /**
     * Get the code.
     *
     * @param  mixed  $value
     * @return string
     */
    public function getCodeAttribute($value)
    {
        return setting('purchase.code_prefix').$value;
    }

    /**
     * Get the vendor that owns the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the purchaseDetail for the Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
