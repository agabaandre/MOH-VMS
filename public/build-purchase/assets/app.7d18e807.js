$(function(){$details=JSON.parse($("#purchase-table tbody").attr("data-details")),$details.length>0?$details.forEach((o,l)=>{u("#purchase-table",o)}):u("#purchase-table"),p(),select2AjaxInit("#vendor_id")});function u(o,l=null){let n=$(o),c=n.data("inventory-category-url"),r=n.data("parts-url"),a=n.find("tbody"),t=a.find("tr").length,e=`<tr id="row-${t}">`;return e+=`<td>
                <select name="collection[${t}][category_id]" id="category-id-${t}" class="form-control" data-ajax-url="${c}" onchange="changeCategory(${t})" data-placeholder="${localize("Choose Inventory Category")}" required >`,l&&l.category&&(e+=`<option value="${l.category.id}" selected >${l.category.name}</option>`),e+=`</select><label class="error" for="category-id-${t}"></label></td>`,e+=`<td>
                <select name="collection[${t}][parts_id]" id="parts-id-${t}" class="form-control" data-ajax-url="${r}" data-placeholder="${localize("Choose Parts")}"  required>`,l&&l.parts&&(e+=`<option value="${l.parts.id}" selected >${l.parts.name}</option>`),e+=`</select><label class="error" for="parts-id-${t}"></label></td>`,e+=`<td>
                <input type="number" class="form-control arrow-hidden" onclick="selectAll(this)" onchange="calculateTotal()" oninput="calculateTotal()"  id="quantity-${t}"
                name="collection[${t}][qty]"
                value="${l?l.qty:"0"}" min="0"
                required>
            </td>`,e+=`<td>
                <input type="number" class="form-control arrow-hidden" onclick="selectAll(this)" onchange="calculateTotal()" oninput="calculateTotal()"  id="unit_price-${t}"
                name="collection[${t}][price]"
                value="${l?l.price:"0.00"}" step=".01" min="0.01"
                required>
            </td>`,e+=`<td class="">
                    <input type="number" class="form-control text-end" id="sub-total-${t}" value="0.00" step=".01" disabled>
                </td>`,e+="<td>",t==0?e+=`<button type="button" class="btn btn-success my-1" onclick="addNewRow('${o}')">
                <i class="fa fa-plus-circle"></i>
            </button>`:e+=` <button type="button" class="btn btn-danger my-1" onclick="deleteRow(${t})">
                <i class="fa fa-trash"></i>
            </button>`,e+="</td>",e+="</tr>",a.append(e),f(t),t}function p(o="#purchase-table"){let n=$(o).find("tbody tr"),c=0;n.each(function(r,a){var i,d;let t=0,e=0,s=0;a=$(a),t=(i=a.find(`#quantity-${r}`).val())!=null?i:0,e=(d=a.find(`#unit_price-${r}`).val())!=null?d:0,s=parseFloat(t)*parseFloat(e),c+=s,a.find(`#sub-total-${r}`).val(s.toFixed(2))}),$(".gross_total").val(c.toFixed(2))}function f(o){var n,c,r;element=`#category-id-${o}`;let l=$(element).find("option").map(function(){return{id:$(this).val(),text:$(this).text()}}).get();$(element).select2({width:"100%",ajax:{placeholder:(n=$(element).data("placeholder"))!=null?n:"",url:(c=$(element).data("ajax-url"))!=null?c:"",dataType:"json",delay:250,data:function(a){return{search:a.term,page:a.page||1}},processResults:function(a,t){return t.page=t.page||1,results=a.data,t.term?(filterOptions=l.filter(function(e){return e.text.toLowerCase().includes(t.term.toLowerCase())}),results=filterOptions.concat(results)):results=l.concat(results),{results,pagination:{more:a.current_page<a.last_page}}},cache:!0},placeholder:(r=localize("Search..."))!=null?r:"Search...",language:{searching:function(){var a;return(a=localize("Loading..."))!=null?a:"Loading..."}}})}
