<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\User;

class BooksController extends Controller
{
	
	//index return the dashboard view with the list of existing books added by the authentified user
     public function index()
    {
        //$books = Book::all();
		$books = auth()->user()->books();
        return view('dashboard', compact('books'));
    }	
   
   
    // This function/method is to create a new book
	
    public function create(Request $request)
    {
        $this->validate($request, [
			'title' => 'required',   //The title of the book is a required field 
            'author' => 'required'	//The author of the book is a required field 		
        ]);
    	$book = new Book(); // an instance of Book
		$book->title = $request->title; //assigned the new book's title
    	$book->author = $request->author; //assigned the new book's author
		$book->user_id = auth()->user()->id; //links the entered book to the current user   	
    	$book->save(); //save the new book into the table books
    	return redirect('/dashboard');  // redirect to dashboard
    }

    // This method is to edit a book author name
    public function edit(Book $book)
    {
		return view('edit', compact('book')); // open the edit blade with the book details
       
    }
    
	// This method is to update a book: either change its author name  or delete  
    public function update(Request $request, Book $book)
    {
    	if(isset($_POST['delete'])) {
    		$book->delete();         
    		return redirect('/dashboard');
    	}
    	else
    	{    //set data in title and author as required
            $this->validate($request, [
				'title' => 'required',
                'author' => 'required'
            ]);
			//get the new data mainly author (as the field of title in edit blade with be set to readonly) and save
			$book->title = $request->title;
    		$book->author = $request->author;
	    	$book->save();  
	    	return redirect('/dashboard');  // then redirect to dashboard after edit
    	}    	
    }
	
	// This method is for the search of a book either by title or author
	public function search(Request $request)
    {
    	/*
		$this->validate($request, [
			'searchText' => 'required',            		
        ]);
		*/
		$searchText=trim($_POST['searchText']);
    	//$results= Book::select(['id','title','author','created_at','updated_at']);
		//$results=Book::all()->where('title', $searchText);	
		$results = Book::Where('title', 'LIKE', '%'.$searchText.'%')->orWhere('author', 'LIKE', '%'.$searchText.'%')->get();
		
		//$results=Book::orderBy('title','desc')->get();
		
        return view('searchResult',compact('results'));
		
    	
    }
	 
	// This function help to sort the books Titles in ASCENDING order
	
	public function sortTitleASC(Request $request)
    {	        
		$results=Book::orderBy('title','ASC')->get();			
		return view('searchResult',compact('results'));
        
    }
	
	// This function help to sort the books Titles in DESCENDING order
	
	public function sortTitleDESC(Request $request)
    {	        
		$results=Book::orderBy('title','DESC')->get();			
		return view('searchResult',compact('results'));
        
    }
	
	// This method help to sort the books Authors in ASCENDING order
	public function sortAuthorASC(Request $request)
    {
		$results=Book::orderBy('author','ASC')->get();
        return view('searchResult',compact('results'));
    }	
	
	// This method help to sort the books Authors in DESCENDING order
	public function sortAuthorDESC(Request $request)
    {
		$results=Book::orderBy('author','DESC')->get();
        return view('searchResult',compact('results'));
    }
	
	
	// This method export all the books with both Titles and Authors  as CSV
	public function exportCSV(Request $request, $type)
	{
	   $books = Book::all(); //select all the books existing in the Books table

        if($type==1){// download both Titles and Descriptions in CSV
		
			$fileName = 'books.csv';		   
			$headers = array(
				"Content-type"        => "text/csv",
				"Content-Disposition" => "attachment; filename=$fileName",
				"Pragma"              => "no-cache",
				"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
				"Expires"             => "0"
			);
				$columns = array('Titles', 'Authors', 'Created_at', 'Updated_at');

				$callback = function() use($books, $columns) {
					$file = fopen('php://output', 'w');
					fputcsv($file, $columns);

					foreach ($books as $book) {
						$row['Title']  = $book->title;
						$row['Author']    = $book->author;
						$row['Created_at']  = $book->created_at;
						$row['Updated_at']  = $book->updated_at;

						fputcsv($file, array($row['Title'],$row['Author'], $row['Created_at'], $row['Updated_at']));
					}
			
					fclose($file);
				};
         }
		 elseif($type==2){ // download only Titles in CSV
			 $fileName = 'booksTitles.csv';		   
				$headers = array(
					"Content-type"        => "text/csv",
					"Content-Disposition" => "attachment; filename=$fileName",
					"Pragma"              => "no-cache",
					"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
					"Expires"             => "0"
				);
			$columns = array('Title');

				$callback = function() use($books, $columns) {
					$file = fopen('php://output', 'w');
					fputcsv($file, $columns);

					foreach ($books as $book) {
						$row['Title']  = $book->title;

						fputcsv($file, array($row['Title']));
					}
						fclose($file);
				};
		}
		 elseif($type==3){ // download only Authors in CSV
		    $fileName = 'booksAuthors.csv';		   
			$headers = array(
				"Content-type"        => "text/csv",
				"Content-Disposition" => "attachment; filename=$fileName",
				"Pragma"              => "no-cache",
				"Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
				"Expires"             => "0"
			);
			$columns = array('Authors');

				$callback = function() use($books, $columns) {
					$file = fopen('php://output', 'w');
					fputcsv($file, $columns);

					foreach ($books as $book) {
						$row['Author']  = $book->author;

						fputcsv($file, array($row['Author']));
					}
						fclose($file);
				};
		}
		else{  //otherwise it will download no file but redirect to dashboard
		    return redirect('/dashboard');
		}
        return response()->stream($callback, 200, $headers);
    }
	
	
		// This method export all the books with both Title and Authors  as XML file
	public function exportXMLall(Request $request)
	{
	    $books = Book::all(); // get all the books
	   $filePath = 'books.xml';
	   $dom     = new \DOMDocument('1.0', 'utf-8'); 
	   $root      = $dom->createElement('tasks');
       	
	  // Generate the XML file from the books list
	   foreach ($books as $book) { 
	     $bookId = $book->id;
		 $bookTitle = $book->title;
		 $bookAuthor    =  $book->author;; 
		  
		 $thebook = $dom->createElement('book');
		 $thebook->setAttribute('id', $bookId);
		 $title     = $dom->createElement('title', $bookTitle); 
		 $thebook->appendChild($title); 
		 $author  = $dom->createElement('author', $bookAuthor); 
		 $thebook->appendChild($author);	 
	 
		 $root->appendChild($thebook);
	   }
	   $dom->appendChild($root); 
	   $dom->save($filePath);
       return response()->download($filePath);// download the XML file generated 

    }
		
		// This method export all only the Titles of the books as XML file
	
	public function exportXMLtitles(Request $request)
	{
	    $books = Book::all();  
          $filePath = 'booksTitles.xml';
		   $dom     = new \DOMDocument('1.0', 'utf-8'); 
		   $root      = $dom->createElement('booksTitles');	  
		   foreach ($books as $book) { 
			 $bookId = $book->id;
			 $bookTitle = $book->title;
			 
			 $thebook = $dom->createElement('booksTitle');
			 $thebook->setAttribute('id', $bookId);
			 $title     = $dom->createElement('title', $bookTitle); 
			 $thebook->appendChild($title);				 
			 $root->appendChild($thebook);			 
		   }		   
		   $dom->appendChild($root); 
		   $dom->save($filePath);
		   return response()->download($filePath);
    }
	
		// This method export all only the Authors of the books as XML file
	
	public function exportXMLauthors(Request $request)
	{
	    $books = Book::all();  
          $filePath = 'booksAuthors.xml';
		   $dom     = new \DOMDocument('1.0', 'utf-8'); 
		   $root      = $dom->createElement('booksAuthors');	  
		   foreach ($books as $book) { 
			 $bookId = $book->id;
			 $bookAuthor = $book->author;
			 
			 $thebook = $dom->createElement('booksAuthor');
			 $thebook->setAttribute('id', $bookId);
			 $author     = $dom->createElement('author', $bookAuthor); 
			 $thebook->appendChild($author);				 
			 $root->appendChild($thebook);			 
		   }		   
		   $dom->appendChild($root); 
		   $dom->save($filePath);
		   return response()->download($filePath);
    }
	
}
