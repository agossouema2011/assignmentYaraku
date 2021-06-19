<?php
use App\Models\Book;
?>
<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

@section('scripts')
<script src="{{ asset('assets/js/actionJS.js') }}"></script>
<script type="text/javascript">
    // your inline script
</script>
@stop


<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-5">
		    <br>
			<!-- Here the form to add a book -->
			<div class="​border-gray-600 border-2 ">
			 <b>Add new book</b><br><br>
				<form method="POST" action="/book" >                
					<div class="form-group">
						Title <font color="red"> * </font>: <input type="text" name="title" size="50" class=""  placeholder='Enter book title'>  
						@if ($errors->has('title'))
							<span class="text-danger">{{ $errors->first('title') }}</span>
						@endif
					</div><br>
					<div class="form-group">
						Author <font color="red"> * </font>: <input type="text" name="author" size="30" class=""  placeholder='Enter author here'>  
						@if ($errors->has('author'))
							<span class="text-danger">{{ $errors->first('author') }}</span>
						@endif
					</div><br>

					<div class="form-group">
						<button type="submit" class="border border-primary rounded" style="background-color: blue; color:white; font-size: 20px; width: 60px;">Add</button>
					</div> 
					<br>
						{{ csrf_field() }}
				</form>
			</div>
				<br>
			<form method="POST" action="/sendforsearch" class="inline-block text-center">
			  <input type="text" name="searchText" size="50" class="border border-primary"  style="height:50px;" placeholder='search for a book by entering its title or author'>  
                                <button type="submit" name="Search" formmethod="POST" class="border border-primary rounded " style="background-color: green; color:white; font-size: 20px; width: 70px; height:50px;">Search</button>
                   {{ csrf_field() }}	             
             </form>
				<br>
				
             <div class="flex-auto text-2xl mb-4" style="text-align:center;">Books List</div>
            
			<div class="flex">
			 	
				<div class="flex-auto text-right ">
						<a href="/sortByTitle" class="border border-primary rounded" style="margin:10px;">Sort Title↑↓</a>
				</div>
				<div class="flex-auto text-right ">
						<a href="/sortByAuthor" class="border border-primary rounded" style="margin:10px;">Sort Author↑↓</a>
				</div>
				<?php 
						    $type=1; // for exporting both Titles and Authors in CSV
				?>
				<div class="flex-auto text-right ">
						<a href="/exportCSV/{{$type}}" id="exportCSV" class="border border-primary rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export CSV</a>
				</div>
				<div class="flex-auto text-right ">
						<a href="/exportXMLall" id="exportXMLall" class="border border-primary rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export XML</a>
				</div>
			</div>
			<br>
			<div class="flex">
				<?php 
						    $type=2; // for exporting only Titles in CSV
				 ?>
				<div class="flex-auto text-right">
						<a href="/exportCSV/{{$type}}" id="exportCSVtitles" class="border border-primary rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export_CSV(Titles)</a>
				</div>
				<?php 
						    $type=3; // for exporting only Authors in CSV
				 ?>
				<div class="flex-auto text-right">
						<a href="/exportCSV/{{$type}}" id="exportCSVauthors" class="border border-primary rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export_CSV(Authors)</a>
				</div>
				<div class="flex-auto text-right ">
						<a href="/exportXMLtitles" id="exportXMLtitles" class="border border-primary  rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export_XML(Titles)</a>
				</div>
				<div class="flex-auto text-right ">
						<a href="/exportXMLauthors" id="exportXMLauthors" class="border border-primary rounded" style="margin:10px;" onclick="eventBooks(event.target);">Export_XML(Authors)</a>
				</div>
			 </div>
		
			<br>
            <table class="w-full rounded mb-4" style="border: 1px solid #ddd;">
                <thead>
                <tr style="border: 1px solid #ddd;">
				    <th  style="background-color: #04AA6D; color: white;">Title</th>
                    <th style="background-color: #04AA6D; color: white; ">Author</th>
                    <th style="background-color: #04AA6D; color: white; ">Actions</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				
               @foreach(auth()->user()->books as $book)
                    <tr class="border-b hover:bg-orange-100">
					    <td >
                            {{$book->title}}
                        </td>
                        <td >
                            {{$book->author}}
                        </td>
                        <td >
                            
                              <a href="/book/{{$book->id}}" name="edit" >Edit</a>
                              
							  <form action="/book/{{$book->id}}" class="inline-block">
                                <button type="submit" name="delete" formmethod="POST" onclick="return confirm('Are you sure you want to delete this item?');">Delete<i class="fa fa-trash"></i></button>
                                {{ csrf_field() }}
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
        </div>
    </div>
</div>
</x-app-layout>