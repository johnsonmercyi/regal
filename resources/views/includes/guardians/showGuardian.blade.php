
<!-- Add Guardian Record Dialog -->
<div id="showGuardianModal" class="modal modal-fixed-footer hide">

    <a href="javacript:void(0)" class="modal-close waves-effect waves-light btn-flat right" id="close">
        <i class="material-icons">close</i>
    </a>

    <div class="modal-content">

        <div class="row">

            <!-- Header -->
            <div class="valign-wrapper mb-5">
                <span class="col s12 flow-text green-text text-darken-1" id="header">
                    Registration Successful!
                </span>
            </div>


            <!-- Contents -->
            <div class="row col s12" id="content">

                <div class="col s12 m6 row valign-wrapper">
                    <i class="material-icons col s2">person</i>
                    <p class="col s10" id="name">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2">favorite</i>
                    <p class="col s10" id="marital_status_id">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2">email</i>
                    <p class="col s10" id="email">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2">contact_phone</i>
                    <p class="col s10" id="phoneNo">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2">business_center</i>
                    <p class="col s10" id="occupation">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2">phone</i>
                    <p class="col s10" id="officePhone">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2 ">home</i>
                    <p class="col s10" id="DOB">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2 ">home</i>
                    <p class="col s10" id="registrationNumber">
                    </p>
                </div>

                <div class="col s12 m6 row valign-wrapper hide">
                    <i class="material-icons col s2 ">home</i>
                    <p class="col s10" id="formerSchoolId">
                    </p>
                </div>

            </div>

        </div>

    </div>

    <div class="modal-footer" id="m-foot">
      <a href="javacript:void(0)" class="modal-close waves-effect waves-red btn-flat" id="editGuardianLink">Edit Guardian</a>
      <a href="javacript:void(0)" class="modal-close waves-effect waves-red btn-flat" id="addGuardianLink">Add Guardian</a>
      <a href="/guardians" class="modal-close waves-effect waves-red btn-flat" id="viewGuardiansLink">View Guardians</a>
    </div>

</div>
