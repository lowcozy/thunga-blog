@extends('layouts.app')

@section('styles')
    <style>
        .label {
            cursor: pointer;
        }

        .progress {
            display: none;
            margin-bottom: 1rem;
        }

        .alert {
            display: none;
        }

        .img-container img {
            max-width: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
          <link  href="{{ asset('css/cropper.css') }}" rel="stylesheet">
@stop

@section('content')


    @include('admin.includes.errors')

    <div class="panel panel-default">
        <div class="panel-heading">
            Edit user Profile
        </div>

        <div class="panel-body">
            <div class="form-group">
                <label class="label" data-toggle="tooltip" title="Change your avatar">
                    <img style="max-width: 200px; border-radius: 50%;" class="rounded" id="avatar"
                         src="{{ $profile->avatar ? url('/' . $profile->avatar)  : "https://avatars0.githubusercontent.com/u/3456749?s=160" }}"
                         alt="avatar">
                    <input type="file" class="sr-only" id="input" name="image" accept="image/*">
                </label>

                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%
                    </div>
                </div>
                <div class="alert" role="alert"></div>
                <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel">Crop the image</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="img-container">
                                    <img id="image"
                                         src="{{ $profile->avatar ? url('/' . $profile->avatar)  : "https://avatars0.githubusercontent.com/u/3456749?s=160" }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="crop">Crop</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="name">UserName</label>
                    <input type="text" name="name" value="{{ $user->name }}" placeholder="Enter Your User Name"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">User Email</label>
                    <input type="email" name="email" value="{{ $user->email }}" placeholder="Enter Your User Email"
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" placeholder="Enter Your new password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="about">About you</label>
                    <textarea class="form-control" name="about" id="about" cols="30"
                              rows="10">{{ $user->profile->about }}</textarea>
                </div>
                <div class="form-group">
                    <label for="facebook">Facebook</label>
                    <input type="text" value="{{ $profile->facebook }}" name="facebook" placeholder=""
                           class="form-control">
                </div>
                <div class="form-group">
                    <label for="facebook">Youtube</label>
                    <input type="text" value="{{ $profile->youtube }}" name="youtube" placeholder=""
                           class="form-control">
                </div>

                <div class="from-group">
                    <div class="text-right">
                        <button class="btn btn-success" type="submit">Update Profile</button>
                    </div>
                </div>


            </form>
            <input type="hidden" value="{{ route('user.upload-avatar') }}" id="url">
        </div>

    </div>

@stop

@section('scripts')
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="{{ asset('js/cropper.js') }}"></script>
    <script>
        const url = $('#url').val();
        window.addEventListener('DOMContentLoaded', function () {
            var avatar = document.getElementById('avatar');
            var image = document.getElementById('image');
            var input = document.getElementById('input');
            var $progress = $('.progress');
            var $progressBar = $('.progress-bar');
            var $alert = $('.alert');
            var $modal = $('#modal');
            var cropper;

            $('[data-toggle="tooltip"]').tooltip();

            input.addEventListener('change', function (e) {
                var files = e.target.files;
                var done = function (url) {
                    input.value = '';
                    image.src = url;
                    $alert.hide();
                    $modal.modal('show');
                };
                var reader;
                var file;
                var url;

                if (files && files.length > 0) {
                    file = files[0];

                    if (URL) {
                        done(URL.createObjectURL(file));
                    } else if (FileReader) {
                        reader = new FileReader();
                        reader.onload = function (e) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            $modal.on('shown.bs.modal', function () {
                cropper = new Cropper(image, {
                    aspectRatio: 1,
                    viewMode: 3,
                });
            }).on('hidden.bs.modal', function () {
                cropper.destroy();
                cropper = null;
            });

            document.getElementById('crop').addEventListener('click', function () {
                var initialAvatarURL;
                var canvas;

                $modal.modal('hide');

                if (cropper) {
                    canvas = cropper.getCroppedCanvas({
                        width: 512,
                        height: 512,
                    });
                    initialAvatarURL = avatar.src;
                    avatar.src = canvas.toDataURL();
                    $progress.show();
                    $alert.removeClass('alert-success alert-warning');
                    canvas.toBlob(function (blob) {
                        var formData = new FormData();

                        formData.append('avatar', blob, 'avatar.jpg');
                        $.ajax(url, {
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,

                            xhr: function () {
                                var xhr = new XMLHttpRequest();

                                xhr.upload.onprogress = function (e) {
                                    var percent = '0';
                                    var percentage = '0%';

                                    if (e.lengthComputable) {
                                        percent = Math.round((e.loaded / e.total) * 100);
                                        percentage = percent + '%';
                                        $progressBar.width(percentage).attr('aria-valuenow', percent).text(percentage);
                                    }
                                };

                                return xhr;
                            },

                            success: function () {
                                $alert.show().addClass('alert-success').text('Upload success');
                            },

                            error: function () {
                                avatar.src = initialAvatarURL;
                                $alert.show().addClass('alert-warning').text('Upload error');
                            },

                            complete: function () {
                                $progress.hide();
                            },
                        });
                    });
                }
            });
        });
    </script>
@stop