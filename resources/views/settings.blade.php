@extends('template.default')
<title>DICO - Settings</title>
@section('content')
<div id="page-content" class="settings" style="min-height: 664px;">
<?php
    $allow_admin = 0;
    $allow_anonymous = 0;
    $company_logo = DEFAULT_COMPANY_LOGO;
    if(!empty($company) && count($company) > 0) {
        $allow_admin = $company->allow_add_admin;
        $allow_anonymous = $company->allow_anonymous;
        if(!empty($company->company_logo)) {
            $company_logo = UPLOAD_PATH.$company->company_logo;
        }
    }
?>
  <div id="wrap">
    <div id="page-heading">
      <ol class="breadcrumb">
        <li><a href="{{ url('/home') }}">Dashboard</a></li>
        <li class="active">Settings</li>
      </ol>
      <h1>Settings</h1>
      <hr>
    </div>
    <div class="container">
        <form name="settings_form" class="common-form" id="settings_form" method="post" action="{{url('/saveSettings')}}" enctype="multipart/form-data">
            <div class="row">
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Allow group admin to give group admin rights</p></div>
          <div class="toggle-button">
              <input type="checkbox" id="allow_admin" name="allow_admin" class="slider-toggle" onclick="saveSettings();" <?php if($allow_admin == 1) { echo "checked";}?>>
            <label class="slider-viewport" for="allow_admin">
              <div class="slider">
                <div class="slider-button">&nbsp;</div>
                <div class="slider-content left"><span>No</span></div>
                <div class="slider-content right"><span>Yes</span></div>
              </div>
            </label>
          </div>
        </div>
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Allow anonymous posting</p></div>
          <div class="toggle-button">
              <input type="checkbox" id="allow_anonymous" name="allow_anonymous" class="slider-toggle" onclick="saveSettings();" <?php if($allow_anonymous == 1) { echo "checked";}?>>
            <label class="slider-viewport" for="allow_anonymous">
              <div class="slider">
                <div class="slider-button">&nbsp;</div>
                <div class="slider-content left"><span>No</span></div>
                <div class="slider-content right"><span>Yes</span></div>
              </div>
            </label>
          </div>
        </div>
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Company Logo:</p></div>
          <div class="company-wrap">
            <div class="img-wrap">
              <img src="{{$company_logo}}" alt="company logo">
            </div>
            <div class="btn-wrap">
              <div class="upload-btn-wrapper">
                <button class="btn st-btn saveSettings">Upload New</button>
                <input type="file" name="file_upload" id="file_upload" class="file-upload__input" onchange="saveSettings()">
              </div>
            </div>

          </div>
        </div>

      </div>
        </form>    
    </div>
  </div>
</div>
@endsection
@push('javascripts')
    <script type="text/javascript">
        function saveSettings(){
            var form_data = new FormData();
            var _token = CSRF_TOKEN;
            var allow_admin = 0;
            var allow_anonymous = 0;
            var file_data = '';
            if($('#allow_admin').prop('checked') == true) {
                allow_admin = 1;
            }
            if($('#allow_anonymous').prop('checked') == true) {
                allow_anonymous = 1;
            }
            if($('#file_upload').prop('files')[0] != null || $('#file_upload').prop('files')[0] != undefined) {
                file_data = $('#file_upload').prop('files')[0];
            }
            form_data.append('allow_admin', allow_admin);
            form_data.append('allow_anonymous', allow_anonymous);
            form_data.append('file_upload', file_data);
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': _token
                }
            });
            console.log(form_data);
            $.ajax({
                url: SITE_URL + '/saveSettings',
                type:"post",
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                success: function(response) {
                    var res = JSON.parse(response);
                    if(res.status == 1) {
                        ajaxResponse('success',res.msg);
                        location.reload();
                    }else {
                        ajaxResponse('error',res.msg);
                    }
                },
                error: function(e) {
                    swal("Error", e, "error");
                }
            });
        }
    </script>
@endpush