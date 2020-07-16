@extends('layouts.app')


@section('content')


    @include('admin.includes.errors')

    <div class="panel panel-default">
        <div class="panel-heading">
            Create a New Post
        </div>

        <div class="panel-body">
            <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" placeholder="Enter Your Blog Title" class="form-control">
                </div>

                <div class="form-group">
                    <label for="category">Select a Category</label>
                    <select name="category_id" id="category_id" class="form-control">
                        @foreach($categories as $category)

                            <option value="{{$category->id}}">{{$category->name}}</option>

                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="tags">Select Tag</label>
                    @foreach($tags as $tag)
                        <div class="checkbox">
                            <label for=""><input name="tags[]" value="{{$tag->id}}" type="checkbox">{{ $tag->tag }}
                            </label>
                        </div>
                    @endforeach

                </div>
                <div class="form-group">
                    <label for="featured">Featured Image</label>
                    <input type="file" name="featured" class="form-control">
                </div>


                <div class="form-group">
                    <label for="body">Content</label>
                    <textarea name="body" id="body" cols="50" rows="20" placeholder="Enter your blog content"
                              class="form-control"></textarea>
                </div>
                <div class="from-group">
                    <div class="text-right">
                        <button class="btn btn-success" type="submit">Publish Post</button>
                    </div>
                </div>


            </form>
            <input type="hidden" value="{{ route('posts.upload') }}" id="url">
        </div>

    </div>

@stop



@section('styles')
    <link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet">

@stop




@section('scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>
    <script src="{{ asset('ckeditor5-build-classic/build/ckeditor.js') }}"></script>

    <script>

        $(document).ready(function () {
            const url = $('#url').val();

            class MyUploadAdapter {
                constructor(loader) {
                    // CKEditor 5's FileLoader instance.
                    this.loader = loader;

                    // URL where to send files.
                    this.url = url;
                }

                // Starts the upload process.
                upload() {
                    return this.loader.file
                        .then(file => new Promise((resolve, reject) => {
                            this._initRequest();
                            this._initListeners(resolve, reject, file);
                            this._sendRequest(file);
                        }));
                }

                // Aborts the upload process.
                abort() {
                    if (this.xhr) {
                        this.xhr.abort();
                    }
                }

                // Example implementation using XMLHttpRequest.
                _initRequest() {
                    const xhr = this.xhr = new XMLHttpRequest();
                    xhr.open('POST', this.url, true);
                    xhr.setRequestHeader("X-CSRF-TOKEN", document.head.querySelector('meta[name="csrf-token"]').content);
                    xhr.responseType = 'json';
                }

                // Initializes XMLHttpRequest listeners.
                _initListeners(resolve, reject, file) {
                    const xhr = this.xhr;
                    const loader = this.loader;
                    const genericErrorText = `Couldn't upload file: ${file.name}.`;

                    xhr.addEventListener('error', () => reject(genericErrorText));
                    xhr.addEventListener('abort', () => reject());
                    xhr.addEventListener('load', () => {
                        const response = xhr.response;

                        // This example assumes the XHR server's "response" object will come with
                        // an "error" which has its own "message" that can be passed to reject()
                        // in the upload promise.
                        //
                        // Your integration may handle upload errors in a different way so make sure
                        // it is done properly. The reject() function must be called when the upload fails.
                        if (!response || response.error) {
                            return reject(response && response.error ? response.error.message : genericErrorText);
                        }

                        // If the upload is successful, resolve the upload promise with an object containing
                        // at least the "default" URL, pointing to the image on the server.
                        // This URL will be used to display the image in the content. Learn more in the
                        // UploadAdapter#upload documentation.
                        resolve({
                            default: response.url
                        });
                    });

                    // Upload progress when it is supported. The file loader has the #uploadTotal and #uploaded
                    // properties which are used e.g. to display the upload progress bar in the editor
                    // user interface.
                    if (xhr.upload) {
                        xhr.upload.addEventListener('progress', evt => {
                            if (evt.lengthComputable) {
                                loader.uploadTotal = evt.total;
                                loader.uploaded = evt.loaded;
                            }
                        });
                    }
                }

                // Prepares the data and sends the request.
                _sendRequest(file) {
                    // Prepare the form data.
                    const data = new FormData();

                    data.append('upload', file);

                    // Important note: This is the right place to implement security mechanisms
                    // like authentication and CSRF protection. For instance, you can use
                    // XMLHttpRequest.setRequestHeader() to set the request headers containing
                    // the CSRF token generated earlier by your application.

                    // Send the request.
                    this.xhr.send(data);
                }
            }

            function MyCustomUploadAdapterPlugin(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                    return new MyUploadAdapter(loader);
                };
            }

            // $('#body').summernote();

            ClassicEditor
                .create(document.querySelector('#body'), {
                    extraPlugins: [MyCustomUploadAdapterPlugin],
                    removePlugins: ['Title'],
                    toolbar: {
                        items: [
                            'heading',
                            '|',
                            'bold',
                            'underline',
                            'fontColor',
                            'fontFamily',
                            'fontSize',
                            'fontBackgroundColor',
                            'italic',
                            'link',
                            'alignment',
                            'bulletedList',
                            'numberedList',
                            '|',
                            'indent',
                            'outdent',
                            'horizontalLine',
                            'removeFormat',
                            'highlight',
                            '|',
                            'imageUpload',
                            'blockQuote',
                            'insertTable',
                            'mediaEmbed',
                            'undo',
                            'redo',
                            'exportPdf',
                            'todoList'
                        ]
                    },
                    language: 'en',
                    image: {
                        toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight' ],

                        styles: [
                            // This option is equal to a situation where no style is applied.
                            'full',

                            // This represents an image aligned to the left.
                            'alignLeft',

                            // This represents an image aligned to the right.
                            'alignRight'
                        ]
                    },
                    table: {
                        contentToolbar: [
                            'tableColumn',
                            'tableRow',
                            'mergeTableCells',
                            'tableCellProperties',
                            'tableProperties'
                        ]
                    },
                    licenseKey: '',

                })
                .then(editor => {
                    window.editor = editor;


                })
                .catch(error => {
                    console.error('Oops, something gone wrong!');
                    console.error('Please, report the following error in the https://github.com/ckeditor/ckeditor5 with the build id and the error stack trace:');
                    console.warn('Build id: tkeoteywoh6k-qdfnji42gn68');
                    console.error(error);
                });
        });
    </script>

@stop
