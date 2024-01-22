<div id='create-article' class='create-article'>
    <form method="POST" id="article-create-form" action="/articles" enctype="multipart/form-data"
        class="bg-white rounded-lg px-10 py-5 text-sm flex flex-col">
        {{ csrf_field() }}

        <div class="field">
            <label id="title-label" for="title" class="text-2xl required">Title</label>
            <input id="title" type="text" name="title" value="{{ old('title') }}" required autofocus>
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
                <option value="" disabled {{old('topic') == '' ? 'selected' : ''}}>Select a Topic</option>
                @foreach ($topics as $topic)
                    @if ($topic->name != 'Undefined')
                        <option value="{{ $topic->id }}" {{old('topic') == $topic->id ? 'selected' : ''}}>{{ $topic->name }}</option>
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
            <textarea class="border-2 border-brown rounded resize-none p-2" id="body" name="body"
                rows=15 required>{{ old('body') }}</textarea>
            @if ($errors->has('body'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('body') }}
                </span>
            @endif
            </div>
        <div class="field">
            <p  class="text-xxs">
                <label for="tags" class="text-xl font-title font-semibold">Tags</label> (Separate tags with a comma, eg.: 'tag1,
                tag2' )
            </p>
            <input id="tags" type="text" name="tags" value="{{ old('tags') }}">
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
                foreach (old('csv') as $image) {
                    if ($image != null) {
                        $images[] = ['id' => $image, 'type' => 'limbo'];
                    }
                }
            }

            $images = json_encode($images);
        @endphp
        <div class="field" id="add-image-section" data-images="{{ $images }}">
            <label>Images
                <input type="file" name="csv[]" class="filepond" multiple />
            </label>
            @if ($errors->has('csv'))
                <span class="error">
                    <i class="bi bi-x-circle text-xxs"></i>
                    {{ $errors->first('csv') }}
                </span>
            @endif
            </div>

        <button type="submit" class="self-center bg-red text-white text-lg rounded px-6 py-1 mt-10 mb-5"
            onclick="disableSubmit(this)">Submit</button>
    </form>
</div>
