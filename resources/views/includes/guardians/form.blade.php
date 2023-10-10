<div class="row" style="padding-bottom: 0; ">

    <!-- add School name may need refactoring -->
    <input type='hidden' name='schoolId' value='1' />

    <!-- Guardian First Name -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">person</i>
    <input id="firstName" name="firstName" type="text" class="validate" value="{{ $guardian->firstName }}">
        <label for="firstname">First Name </label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>

    <!-- Guardian Surname Name -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">perm_identity</i>
    <input id="lastName" name="lastName" type="text" class="validate" value="{{ $guardian->lastName }}">
        <label for="surname">Surname </label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>

    <!-- Guardian Other Names -->
    <div class="input-field col s12">
        <i class="material-icons prefix">all_inclusive</i>
    <input id="otherName" name="otherName" type="text" class="validate" value="{{ $guardian->otherName }}">
        <label for="othernames">Other Names </label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>


    <!-- Title -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">title</i>
        <select name="title" id="title" class="validate">

            <option value="Mr.">Mr.</option>
            <option value="Mrs.">Mrs.</option>
            <option value="Miss.">Miss.</option>

        </select>
        <label for="title">Title</label>
        <span class="helper-text" data-error="This field is required!" data-success=""></span>
    </div>

    <!-- Marital Status -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">favorite</i>
        <select name="marital_status_id" id="maritalStatusId" class="validate">


            @foreach ($marital_statuses as $marital_status)
                @if($marital_status->id == $guardian->marital_status_id)
                <option value="{{ $guardian->maritalStatusId }}" selected>{{ $marital_status->maritalStatus }}</option>
                @else
                <option value="{{ $marital_status->id }} {{ $marital_status->id === $guardian->marital_status_id ? 'selected' : '' }}">{{ $marital_status->maritalStatus }}</option>
                @endif
            @endforeach

        </select>
        <label for="maritalStatusId">Marital Status</label>
        <span class="helper-text" data-error="This field is required!" data-success=""></span>
    </div>

    <!-- Guardian Email -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">email</i>
        <input id="Email" name="email" type="email" class="validate" value="{{ $guardian->email }}">
        <label for="email">Email</label>
        <span class="helper-text" data-error="Invalid email address"></span>
    </div>


    <!-- Guardian Phone -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">contact_phone</i>
        <input id="phoneNo" name="phoneNo" type="text" class="validate" value="{{ $guardian->phoneNo }}">
        <label for="phone">Phone</label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>

    <!-- Guardian Occupation -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">business_center</i>
        <input id="occupation" name="occupation" type="text" class="validate" value="{{ $guardian->occupation }}">
        <label for="occupation">Occupation</label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>

    <!-- Guardian Office Phone -->
    <div class="input-field col s12 l6">
        <i class="material-icons prefix">phone</i>
        <input id="officePhone" name="officePhone" type="text" class="validate" value="{{ $guardian->officePhone }}">
        <label for="officePhone">Office Phone</label>
        <span class="helper-text" data-error="This field is required!"></span>
    </div>



    <!-- Guardian Office Address -->
    <div class="input-field col s12">
        <i class="material-icons prefix">home</i>
        <input id="officeAddress" name="officeAddress" type="text" class="validate" value="{{ $guardian->officeAddress }}">
        <label for="officeAddress">Office Address</label>
        <span class="helper-text" data-error="This field is required!" data-success=""></span>
    </div>


</div>
