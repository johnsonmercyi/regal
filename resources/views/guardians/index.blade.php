 {{-- @extends('layout')  --}}

@extends('schools.layout.schoollayout')

@section('title', 'Parents/Guardians')

@section('content')

    <div class="datatableContainer">

        <div>
            <h5>Parents/Guardians' List</h5>
        </div>

        <table class="display responsive-table white z-depth-1" id="guardianData" style="border-radius: 2px;" width="100%">
            <thead class="center-align">
                <tr>
                    <th>id</th>
                    <th>Father</th>
                    <th>Father's Phone</th>
                    <th>Mother</th>
                    <th>Mother's Phone</th>
                    <th>Family Doctor</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                @foreach ($guardians as $guardian)
                    <tr>
                        <td>{{ $guardian->id }}</td>
                        <td>{{ strtoupper($guardian->father_title.' '.$guardian->father_firstName.' '.$guardian->father_lastName.' '.$guardian->father_otherName) ?? 'N/A' }}</td>
                        <td>{{ $guardian->father_phoneNo ?? 'N/A' }}</td>
                        <td>{{ strtoupper($guardian->mother_title.' '.$guardian->mother_firstName.' '.$guardian->mother_lastName.' '.$guardian->mother_otherName) ?? 'N/A' }}</td>
                        <td>{{ $guardian->mother_phoneNo ?? 'N/A' }}</td>
                        <td>{{ strtoupper($guardian->family_doctor_name) ?? 'N/A' }}</td>
                        <td class="center-align">
                             <a href="/{{$school->id}}/guardians/{{$guardian->id}}/view" title="View Parent" class="btn btn-floating waves-effect waves-light colCode viewGuardian" id="{{ 'viewGuardian'. $guardian->id }}">
                                <i class="material-icons">pageview</i>
                            </a>
                        
                            <a href="#" title="Edit Parent" class="btn btn-floating waves-effect waves-light colCode editGuardian">
                                <i class="material-icons">edit</i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('dialog')
    @include('includes.guardians.showGuardian')
@endsection

