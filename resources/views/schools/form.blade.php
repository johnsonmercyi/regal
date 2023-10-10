<div class="input-field col s12 l6">
        <input type="text" id="schoolName" name="schoolName" required placeholder="Enter Name of School">
        <label for="schoolName">School Name</label>
    </div>
    <div class="input-field col s12 l6">
        <input type="text" id="schoolAddress" name="schoolAddress" required placeholder="Enter Address of School">
        <label for="schoolAddress">Address</label>
    </div>
    <div class="input-field col s12 l4">
        <input type="text" id="motto" name="motto" required placeholder="Enter Motto of School">
        <label for="motto">Motto</label>
    </div>
    <div class="input-field col s12 l4">
        <input type="text" id="principalName" name="principalName" required placeholder="Enter Name of School Principal">
        <label for="principalName">Name of Principal</label>
    </div>
    <div class="input-field col s12 l4">
        <select name="region_id" id="region_id">
            <option value="" disabled selected>Region</option>
            @foreach ($regions as $region)
                <option value="{{ $region->id }}">{{ $region->name }}</option>
            @endforeach
        </select>
    </div> 
    <div class="file-field input-field col s12 l6">
        <div class="btn">
            <span>Logo</span>
            <input type="file" name="logo" id="logo" >
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Upload School Logo">
        </div>
    </div>
    <div class="file-field input-field col s12 l6">
        <div class="btn">
            <span>Signature</span>
            <input type="file"  name="signature" id="signature" >
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" placeholder="Upload School Signature">
        </div>
    </div>

   
    