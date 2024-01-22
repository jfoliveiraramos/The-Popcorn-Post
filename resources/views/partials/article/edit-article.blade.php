<div id='edit-article' class='edit-article'>
    <form method="POST" action='/articles/{{ $article->id }}' enctype="multipart/form-data"
        class="bg-white rounded-lg px-10 py-5 text-sm flex flex-col">
        {{ method_field('patch') }}
        {{ csrf_field() }}

        <div class="field">
            <label id="title-label" for="title" class="text-2xl required">Title{{old('topic')}}</label>
            <input id="title" type="text" name="title" value="{{ old('title') !== null ? old('title') : $article->title }}" required autofocus>
            @if ($errors->has('title'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('title') }}
                </span>
            @endif
            </div>
        <div class="field">
            <label for="topic" class="required">Topic</label>
            <select id="topic" name="topic" required
                class="border-2 border-brown bg-white text-base p-2 w-min rounded">
                @foreach ($topics as $topic)
                    @if ($topic->name != 'Undefined')
                        <option value="{{ $topic->id }}" 
                            {{ (old('topic') !== null ? old('topic') == $topic->id : $topic->id === $article->topic->id) ? 'selected' : '' }}>
                            {{ $topic->name }}</option>
                    @endif
                @endforeach
            </select>
            @if ($errors->has('topic'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('topic') }}
                </span>
            @endif
            </div>
        <div class="field">
            <label for="body" class="required">Body</label>
            <textarea rows=15 id="body" type="text" name="body" required
                class="border-2 border-brown rounded resize-none p-2">{{ old('body') !== null ? old('body') : $article->body() }}</textarea>
            @if ($errors->has('body'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('body') }}
                </span>
            @endif
            </div>
        <div class="field">
            <p for="tags" name="tags" class="text-xxs">
                <label for="tags" class="text-xl font-title font-semibold">Tags</label> (Separate tags with a comma, eg.: 'tag1,
                tag2' )
            </p>
            <input id="tags" type="text" name="tags" value="{{ $tags }}">
            @if ($errors->has('tags'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('tags') }}
                </span>
            @endif
            </div>

        @php
            $images = [];

            if (old('csv') !== null) {
                foreach (old('csv') as $id) {
                    if ($id != null) {
                        if (file_exists(public_path('images/tmp/' . $id . '/'))) {
                            $images[] = ['id' => $id, 'type' => 'limbo'];
                        } else {
                            $images[] = ['id' => $id, 'type' => 'local'];
                        }
                    }
                }
            } else {
                foreach ($article->images as $image) {
                    $images[] = ['id' => $image->file_name, 'type' => 'local'];
                }
            }

            $images = json_encode($images);
        @endphp

        <div class="field" id="add-image-section" data-images="{{ $images }}">
            <label for="images">Images
            <input type="file" name="csv[]" class="filepond" multiple />
            </label>
            @if ($errors->has('csv'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('csv') }}
                </span>
            @endif
            </div>

        <button type="submit" class="bg-red text-white rounded px-2 py-1 w-fit self-center"
            onclick="disableSubmit(this)">Save Changes</button>
    </form>
</div>
