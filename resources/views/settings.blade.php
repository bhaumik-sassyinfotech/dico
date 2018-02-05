@extends('template.default')
@section('content')
<div id="page-content" class="settings" style="min-height: 664px;">
  <div id="wrap">
    <div id="page-heading">
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
        <li class="active">Settings</li>
      </ol>
      <h1>Settings</h1>
      <hr>
    </div>
    <div class="container">
      <div class="row">
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Require admin approval before posting</p></div>
          <div class="toggle-button">
            <input type="checkbox" id="1" class="slider-toggle">
            <label class="slider-viewport" for="1">
              <div class="slider">
                <div class="slider-button">&nbsp;</div>
                <div class="slider-content left"><span>Yes</span></div>
                <div class="slider-content right"><span>No</span></div>
              </div>
            </label>
          </div>
        </div>
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Allow group admin to give group admin rights</p></div>
          <div class="toggle-button">
            <input type="checkbox" id="2" class="slider-toggle">
            <label class="slider-viewport" for="2">
              <div class="slider">
                <div class="slider-button">&nbsp;</div>
                <div class="slider-content left"><span>Yes</span></div>
                <div class="slider-content right"><span>No</span></div>
              </div>
            </label>
          </div>
        </div>
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Allow anonymous posting</p></div>
          <div class="toggle-button">
            <input type="checkbox" id="3" class="slider-toggle">
            <label class="slider-viewport" for="3">
              <div class="slider">
                <div class="slider-button">&nbsp;</div>
                <div class="slider-content left"><span>Yes</span></div>
                <div class="slider-content right"><span>No</span></div>
              </div>
            </label>
          </div>
        </div>
        <div class="toggle-wrap">
          <div class="toggle-text"><p>Company Logo:</p></div>
          <div class="company-wrap">
            <div class="img-wrap">
              <img src="assets/img/company-sp-logo.PNG" alt="company logo">
            </div>
            <div class="btn-wrap">
              <div class="upload-btn-wrapper">
                <button class="btn st-btn">Upload New</button>
                <input name="myfile" type="file">
              </div>
              <p class="text-10">The file should be less than 1 mb &amp; within 100x100 px.</p>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection