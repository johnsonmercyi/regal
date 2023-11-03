<?php

use App\Http\Controllers\GuardianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Routes a view.
 * First param is the name used to view the page on the browser
 * while the Second param is the name of the view itself.
 *
 * However this shortcut way of routing views is not ideal for
 * passing data or parameters to the view.
 */

// Route::view('/', 'home');

Route::view('contact', 'contact');

Route::view('about', 'about');

/**
 * The normal method of routing is ideal for passing data to the view
 *
 * The controller class takes care of the view logic:
 * e.g: CustomersController is the customer controller class
 *      while the @list simply means the method in the controller
 *      class that returns some data to the view.
 */

// For viewing the component
// Route::get('guardians', 'GuardianController@index');
// Route::get('guardians/create', 'GuardianController@create');


Route::get('login', 'LoginPageController@index')->name('login');
Route::get('/', 'LoginPageController@index');
Route::post('login/user', 'LoginPageController@mainLogin');


Route::middleware(['auther'])->group(function () {
  Route::get('{school}/student/profile/{student}', 'StudentsController@studentProfile')->name('studentProfile');

  Route::get('{school}/staff/profile/{staff}', 'StaffController@staffProfile')->name('staffProfile');
  Route::post('student/result/{student}', 'ResultsController@fetchStudentResult');
});

Route::middleware(['auther', 'adminCheck'])->group(function () {

  Route::post('guardians', 'GuardianController@store');
  Route::get('/{school}/guardians/{guardian}/view', 'GuardianController@show');
  Route::get('guardians/{guardian}/edit', 'GuardianController@edit');
  Route::patch('guardians/{guardian}', 'GuardianController@update');
  Route::delete('guardians/{guardian}', 'GuardianController@destroy');

  Route::get('{school}/archive', 'ArchiveController@archiveView');
  Route::post('archive/student/search', 'ArchiveController@searchStudents');
  Route::post('archive/staff/search', 'ArchiveController@searchStaff');
  Route::post('archive/student/restore', 'ArchiveController@restoreStudent');
  Route::post('archive/staff/reactivate', 'ArchiveController@restoreStaff');

  Route::get('schools/{school}/guardians', 'SchoolsController@guardian');
  Route::get('{school}/guardians/{guardian}/edit', 'SchoolsController@guardianEdit');
  Route::resource('schools', 'SchoolsController');
  Route::post('schools/term/resume', 'SchoolsController@resumeDate');
  Route::get('{school}/newsletter/create', 'SchoolsController@newsletterView');
  Route::get('{school}/newsletter/view', 'SchoolsController@viewNewsletter');
  Route::post('/newsletter/upload', 'SchoolsController@newsletterStore');
  Route::post('schools/update/{school}', 'SchoolsController@update');

  Route::post('{school}/counts', 'StudentsController@countFunc');
  Route::get('{school}/students/create', 'StudentsController@create');
  Route::post('students/store', 'StudentsController@store');
  Route::get('{school}/students', 'StudentsController@index');
  Route::get('{school}/students/{student}/edit', 'StudentsController@edit');
  Route::get('{school}/student/{student}', 'StudentsController@details');
  Route::patch('students/{student}', 'StudentsController@update');
  Route::post('student/guardian/{school}/{student}', 'StudentsController@getGuardian');
  Route::get('{school}/students/remove', 'StudentsController@removeView');
  Route::post('students/remove', 'StudentsController@remove');
  Route::get('{school}/students/capture', 'StudentsController@showCapture');
  Route::post('/student/photo/update/{student}', 'StudentsController@photoUpdate');


  Route::get('{school}/staff/create', 'StaffController@create');
  Route::get('{school}/staff', 'StaffController@index');
  Route::post('staff/store', 'StaffController@store');
  Route::post('staff/update/{staff}', 'StaffController@update');
  Route::get('{school}/staff/{staff}/details', 'StaffController@details');
  Route::get('{school}/staff/{staff}/edit', 'StaffController@edit');
  Route::get('{school}/staff/capture', 'StaffController@captureShow');
  Route::post('/staff/signature/update/{staff}', 'StaffController@signatureUpdate');
  Route::post('/staff/photo/update/{staff}', 'StaffController@photoUpdate');
  Route::get('{school}/staff/remove', 'StaffController@removeView');
  Route::post('staff/remove', 'StaffController@remove');

  Route::get('{school}/class', 'ClassroomController@index');
  Route::post('class/{classroom}/members/move', 'ClassroomController@getMembers');
  Route::post('class/formTeacher/store', 'ClassroomController@formTeacher');
  Route::post('/class/students/move', 'ClassroomController@moveToClass');
  Route::get('{school}/students/class', 'ClassroomController@changeClass');
  Route::get('{school}/class/create', 'ClassroomController@create');
  Route::post('class/new/store', 'ClassroomController@store');
  Route::get('{school}/student/term/status', 'ClassroomController@viewTermStatus');
  Route::post('students/status/fetch', 'ClassroomController@fetchStudentsByTerm');
  Route::post('students/status/change', 'ClassroomController@updateStatus');

  Route::post('result/students/{classroom}', 'ScoreController@getStudents');
  Route::post('result/nursery_students/{classroom}', 'NurseryScoresController@getStudents');
  Route::get('{school}/result', 'ScoreController@index');
  Route::put('{school}/result', 'ScoreController@insertScores');
  Route::patch('{school}/result', 'ScoreController@updateStudentScores');
  Route::get('scores/network/check', 'ScoreController@networkCheck');

  Route::get('{school}/nursery_score', 'NurseryScoresController@index');

  // Section heads
  Route::get('{school}/section_heads/index', 'SectionHeadsController@index');
  Route::post('{school}/section_heads/create', 'SectionHeadsController@store');
  Route::post('{school}/section_heads/{section_head}/edit', 'SectionHeadsController@update');
  Route::post('{school}/section_heads/{section_head}/delete', 'SectionHeadsController@destroy');


  Route::get('{school}/section/create', 'SchoolSectionController@create');
  Route::get('{school}/sections', 'SchoolSectionController@index');
  Route::get('{school}/{sectionId}/section/edit', 'SchoolSectionController@edit');
  // Route::get('{school}/staff', 'StaffController@index');
  // Route::post('section/store', 'SchoolSectionController@store');
  Route::post('section/new/store', 'SchoolSectionController@store');

  Route::get('{school}/assessformat/create', 'AssessmentsController@create');
  Route::post('{school}/assessformat', 'AssessmentsController@store');
  Route::post('assess/format/assign', 'AssessmentsController@assignAssessFormat');

  Route::get('{school}/trait', 'TraitsController@manageTrait');
  Route::post('{school}/trait/manage', 'TraitsController@storeSchoolTrait');
  Route::get('{school}/trait/format', 'TraitsController@createTraitFormat');
  Route::get('{school}/trait/format/view', 'TraitsController@index');
  Route::post('{school}/trait/format', 'TraitsController@storeTraitFormat');
  Route::get('{school}/trait/assessment', 'TraitsController@makeStudentTraitAssessment');
  Route::post('traits/students/{school}/{classroom}', 'TraitsController@getStudents');
  Route::post('traitAssessment/store', 'TraitsController@storeStudentAssessment');
  Route::post('traits/update', 'TraitsController@update');

  Route::get('{school}/makecomments',  'CommentsController@makeClassComments');
  Route::post('comments/class/{classroom}',  'CommentsController@getCommentsClass');
  Route::post('comments/submit/{student}',  'CommentsController@insert');

  Route::get('{school}/class/results', 'ResultsController@selectPage');
  Route::post('results/class/{classroom}', 'ResultsController@fetchResults');
  Route::get('{school}/results/students', 'ResultsController@studentSelectPage');
  Route::get('{school}/results/manage', 'ResultsController@manageResultPage');
  Route::post('results/students/{class}', 'ResultsController@fetchClassList');
  // Route::post('student/result/{student}', 'ResultsController@fetchStudentResult');
  Route::post('delete/result/{student}', 'ResultsController@deleteStudentResult');
  Route::post('class/result/{classroom}', 'ResultsController@fetchClassResult');


  Route::get('{school}/grades/create', 'GradesController@create');
  Route::post('{school}/grades/store', 'GradesController@store');
  Route::get('{school}/grades/index', 'GradesController@index');
  Route::get('{school}/{section}/grades/edit', 'GradesController@edit');
  Route::post('grade/format/assign', 'GradesController@assignGradeFormat');

  Route::get('{school}/subjects/assign', 'SubjectsController@assignView');
  Route::get('{school}/subjects/order', 'SubjectsController@subjectOrder');
  Route::get('{school}/subjects/class/list', 'SubjectsController@classAssignList');
  Route::post('subjects/order/store', 'SubjectsController@orderStore');
  Route::post('subjects/class/store', 'SubjectsController@assignClassStore');
  Route::get('subjects/class/{school}/{classroom}/{academicSession}/{term}', 'SubjectsController@classAssignView');
  // Route::get('subjects/class/', 'SubjectsController@classAssignSelect');
  Route::post('subjects/students/{classroom}', 'SubjectsController@selectStudents');
  Route::post('subjects/submitassign', 'SubjectsController@assignStudentsStore');
  Route::post('subjects/section/assign', 'SubjectsController@sectionClasses');
  Route::post('subjects/section/store', 'SubjectsController@sectionSubjectStore');
  Route::post('/subjects/section/get', 'SubjectsController@sectionSubjects');
  Route::post('/subjects/class/remove', 'SubjectsController@removeSubjectAssign');
  Route::get('{school}/subjects/class/assign', 'SubjectsController@classAssignSelect');
  Route::post('subjects/class/assign/many', 'SubjectsController@assignSubjectsClassStore');


  Route::get('logout', 'LoginPageController@logout');
  Route::get('{school}/user/password', 'LoginPageController@changePassword');
  Route::post('user/repassword', 'LoginPageController@repassword');

  Route::get('{school}/inventory/create', 'InventoryController@create');
  Route::get('{school}/inventory/sale', 'InventoryController@sale');
  Route::get('{school}/inventory/stock', 'InventoryController@stock');
  Route::get('{school}/inventory/reports', 'InventoryController@reports');
  Route::get('{school}/inventory/categories', 'InventoryController@categoryIndex');
  Route::post('inventory/category/create', 'InventoryController@categoryCreate');
  Route::post('inventory/item/create', 'InventoryController@itemCreate');
  Route::post('inventory/invoice/store', 'InventoryController@invoiceStore');
  Route::get('{school}/inventory/reports/index', 'InventoryController@reportsIndex');
  Route::get('{school}/inventory/reports/daily', 'InventoryController@reportDaily');
  Route::get('{school}/inventory/reports/stock', 'InventoryController@stockReport');
  Route::get('{school}/inventory/reports/invoice', 'InventoryController@invoiceReport');
  Route::post('inventory/report/daily/fetch', 'InventoryController@fetchDailyReport');
  Route::post('inventory/report/invoice/fetch', 'InventoryController@fetchInvoiceReport');



  Route::get('{school}/hostel/assign', 'HostelController@assignView');
  Route::post('hostel/student/verify', 'HostelController@assignVerify');
  // Route::post('{school}/assessformat', 'AssessmentsController@store');

  Route::get('{school}/assignments/create', 'AssignmentsController@create');
  Route::get('{school}/assignments/view', 'AssignmentsController@view');
  Route::post('/assignments/store', 'AssignmentsController@store');
  Route::get('/assignments/download/{fileName}', 'AssignmentsController@download');

  Route::get('{school}/sync', 'SyncController@index');
  Route::post('sync/table', 'SyncController@offlineSys');
  Route::post('sync/update', 'SyncController@offlineUpdate');
  Route::post('sync/pix/{school}', 'SyncController@offlinePix');

  Route::get('{school}/passwords', 'SchoolsController@viewPasswordGen');
  Route::post('generate/passwords', 'SchoolsController@generatePasswords');


  //Timetable Routes
  //Route::get('{school}/addtimetable', 'TimetableController@addTimeTable');
  Route::get('{school}/viewtimetable', 'TimetableController@viewTimeTable');
  Route::get('{school}/addtimetable', 'TimetableController@AddTimeTable');
  Route::get('{school}/manageperiod', 'TimetableController@manageperiod');
  Route::post('/get/timetable', 'TimetableController@getTimetable');
  //Route::get('Nigerian-states', 'TimetableController@naijastates');
  Route::post('/period/store', 'TimetableController@storeperiod');
  Route::post('/timetable/store', 'TimetableController@storeTimeTable');

  // Payroll Routes
  Route::get('{school}/staff/salary', 'PayrollController@salaryIndex');
  Route::post('salary/store', 'PayrollController@storeSalary');
  Route::get('{school}/payroll/create', 'PayrollController@payrollView');
  Route::post('payroll/start', 'PayrollController@getStaff');
  Route::post('payroll/store', 'PayrollController@storePayroll');
  Route::get('{school}/payroll/report', 'PayrollController@reportView');
  Route::post('payroll/report', 'PayrollController@reportFetch');
  Route::get('{school}/payroll/structure', 'PayrollController@structureView');
  Route::post('payroll/structure/store', 'PayrollController@storeStructure');
  Route::post('payroll/structure/activate', 'PayrollController@activateStructure');

  Route::get('{school}/promotion', 'StudentsController@promotionView');
  Route::post('students/promote', 'StudentsController@promote');

  Route::get('{school}/nursery_results/students_view', 'NurseryResultController@showStudentsView');
  Route::get('{school}/nursery_results/student_result_view', 'NurseryResultController@showStudentResultView');
  Route::get('{school}/nursery_results/students_view/class_students', 'NurseryResultController@fetchStudents');

  /***** CHINWATAKWEAKU ROUTES***** */
  Route::get('{school}/subjects/nurserysubjects', 'SubjectsController@nurserySubjects');
  Route::post('subjects/nurserysubjectCategory', 'SubjectsController@nurserySubjectCategory');
  Route::post('subjects/nurserysubject', 'SubjectsController@nurserySubject');
  /***** CHINWATAKWEAKU***** */

  // Shw Controller Routes
  Route::get('{school}/students/hw/read', 'ShwController@index');
  Route::post('{school}/students/hw/create', 'ShwController@create');
  Route::put('{school}/students/hw/update', 'ShwController@update');
  Route::delete('{school}/students/hw/delete', 'ShwController@delete');
  Route::post('{school}/students/hw/search', 'ShwController@search');


  // Route::resource('guardians', 'guardianController');
});
