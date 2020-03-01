<form action="" @submit.prevent="updateElement()"  id="tax_element_edit" method="post">
    <fieldset>
        <div class="form-group col-md-12">
            <label class="form-label" >Name </label>
            <input class="form-control"  type="text" v-model="elements_form.element_name" >
        </div>
        <div class="form-group col-md-12">
            <label class="form-label" for="authority">Authority</label>
            <input class="form-control" id="authority" :value="authority.name"  type="text" disabled>
        </div>

        <div class="form-group col-md-12">
            <label class="form-label" >Frequency</label>
            <select  v-model="elements_form.frequency"  class="form-control custom-select " tabindex="-1" required  @change="toggleFrequency($event)">
                <option selected :value="elements_form.frequency"  >@{{ elements_form.frequency }}</option>
                <option value="yearly">Yearly</option>
                <option value="monthly">Monthly</option>
            </select>
        </div>
        <div class="form-group col-md-4 " v-if="elements_form.isYearly">
            <label class="form-label" >Add Run  Month and Date </label>
            <input  type="date"  class="form-control" v-model="elements_form.frequency_year" >
        </div>
        <div class="form-group col-md-12" v-if="elements_form.isMonthly">
            <label class="form-label" for="element_type">Add Tax Run Day </label>
            <input  type="number" class="col-md-4 form-control mr-4 mt-3" v-on:keyup="validateDay($event)"  v-model="elements_form.frequency_month " placeholder="Add Tax Run Day eg (21)" required>
        </div>
        <div class="form-group col-md-12">
            <label class="form-label" for="element_type">Element Type</label>
            <select  v-model="elements_form.element_type" class="form-control custom-select " tabindex="-1" required  @change="toggleElementType($event)">
                <option selected :value="elements_form.element_type"  > @{{ elements_form.element_type }} </option>
                <option value="percentage">Percentage</option>
            </select>
        </div>
        <div class="form-group col-md-12" v-if="elements_form.isPercent">
            <label class="form-label" for="element_type">Add Tax Percentage Value </label>
            <input type="hidden"   v-model="elements_form.type_data.element_type = 'Percentage'"  />
            <input type="number"  class=" col-md-4 form-control mr-4 mt-3"  v-model="elements_form.type_data.value " placeholder="Add Value eg (5) " required/>
        </div>
        <div>
            <div class="form-group col-md-12">
                <label class="form-label">Select Accounts</label>
                <select  id="select-tags-accounts" @change="getAccounts($event)" class="form-select" multiple required>
                    {{--                                    <option v-for="account in accounts" v-if="inArray(account,elements_form.accounts)" :value="account.id">@{{ account.display_name }}</option>--}}
                </select>

            </div>
        </div>
    </fieldset>
</form>