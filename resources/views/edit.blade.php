<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Book') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
        
            <form method="POST" action="/book/{{ $book->id }}">
				
				<div class="form-group">
                    Title <font color="red">*</font>: <input type="text" name="title" size="30" value="{{$book->title }}" readonly="true" >	
                    @if ($errors->has('title'))
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                    @endif
                </div>
                <br>
                <div class="form-group">
                    Author <font color="red">*</font>:<input type="text" name="author" class="" value="{{$book->author }}">
                    @if ($errors->has('author'))
                        <span class="text-danger">{{ $errors->first('author') }}</span>
                    @endif
                </div>
                <br>
                <div class="form-group">
                    <button type="submit" name="update" class="border border-primary rounded" style="background-color: blue; color:white; font-size: 20px; width: 100px;">Update</button>
                </div>
				<br>
            {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>
</x-app-layout>