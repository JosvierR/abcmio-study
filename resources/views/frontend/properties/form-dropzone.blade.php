<form id="upload-file" class="dropzone" action="{{ $postRoute }}" enctype="multipart/form-data" method="POST">
    @csrf
    @method("POST")
    <input type="hidden" id="type" name="type" value="{{ $type }}">
    <div class="dz-message">
        <h1 class="display-4">
            <i class=" mdi mdi-progress-upload"></i>
        </h1>
        Drop files here or click to upload.<BR>
        <SPAN class="note needsclick">(This is just a demo dropzone. Selected
                                    files are <STRONG>not</STRONG> actually uploaded.)</SPAN>
            <div class="p-t-5">
                <a href="#" class="btn btn-lg btn-primary">Upload File</a>
            </div>
    </div>
</form>
