<?php

namespace App\Http\Controllers;

use App\Models\Etape;
use App\Models\Image;
use App\Models\Produit;
use App\Models\Recette;
use App\Models\Categorie;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class RecetteController extends Controller
{
    //
    public function index(){
        $produits = Produit::all();
        $categories = Categorie::all();
        return view('addrecette',compact('produits','categories'));
     }
 
      public function recettedetail($id){
          
          $recette = Recette::find($id);
          $ingredients = $recette->ingredients;
          $etapes = $recette->etapes;
          $medias= $recette->medias;
          $comments = $recette->comments->reverse();
          $comment = $recette->comments->count();
          return view('recettepage',['recette'=>$recette , 'ingredients'=>$ingredients, 'etapes'=>$etapes, 'medias' =>$medias,'comments'=>$comments,'comment'=>$comment]);
      }

      public function show($id){
          
        $recette = Recette::find($id);
        $ingredients = $recette->ingredients;
        $etapes = $recette->etapes;
        $medias= $recette->medias;
        $comments = $recette->comments->reverse();
        $comment = $recette->comments->count();
        return view('recettedetail',['recette'=>$recette , 'ingredients'=>$ingredients, 'etapes'=>$etapes, 'medias' =>$medias, 'comments' =>$comments, 'comment' =>$comment]);
    }
      
     public function list_recette(){
         $recettes = Recette::where('user_id','=',Auth::user()->id)->get();
         $categories = Categorie::all();
         return view('recette_cuisinier',['recettes'=>$recettes],['categories'=>$categories]);
     }

     public function list_recette_admin(){
        $recettes = Recette::all();
        for($i = 1; $i < 13; $i++) {
            $nbr = Recette::whereMonth('created_at', $i)->count();
            $values[$i] = $nbr;

        }
        return view('recette_admin',['recettes'=>$recettes, 'values'=>$values]);
    }
     
     public function store(Request $request){
        
         
        $hasFile = $request->hasFile('picture');

          
        if($hasFile){
          $file =  $request->file('picture');
          $name = $file->store('recettePicture');
          $lien =  Storage::url($name);
         
        }
    
         $recette = new Recette();
         $recette->user_id = Auth::user()->id; 
         $recette->titre = $request['titre'];
         $recette->categorie_id = $request['categorie'];
         $recette->temps_preparation = $request['temps_preparation'];
         $recette->temps_cuisson = $request['temps_cuisson'];
         $recette->cout = $request['cout'];
         $recette->difficulte = $request['difficulte'];
         $recette->save();
         
        for($j = 1; $j <=$request->nbringrd ; $j++){
            $ingredient = new Ingredient();
         
            $ingredient->quantite = $request['quantite'];
            $ingredient->produit_id = $request['produits'];
            $ingredient->unite = $request['unite'];
            $recette->ingredients()->save($ingredient);

        }
   
        //Insertion des etapes
                
        for ($i = 1; $i <= $request->nbr ; $i++) {
            $etape= new Etape();
            $etape->description = $request['step'.$i];
            $recette->etapes()->save($etape);
        }

        //insertion des images
        for($n = 1; $n <= $request->nbrmedia ; $n++){
           $media = new Image;
           $media->lien = $lien;
            
            
           $recette->medias()->save($media);
        
        }
           return redirect('/recettes_cuisinier')->with('success','Recette ajoutée avec success!');
      
     }

     public function edit($id)
    {
        $recette = Recette::find($id);
        $produits = Produit::all();
        $categories = Categorie::all();
        return view ('edit_recette',['recette'=>$recette,'produits'=>$produits,'categories'=>$categories]);



    }

    public function update(Request $request, $id){

      
        
    	$recette = Recette::find($id);
    	$recette->titre = $request['titre'];
    	$recette->categorie_id = $request['categorie'];
        $recette->temps_preparation = $request['temps_preparation'];
        $recette->temps_cuisson = $request['temps_cuisson'];
        $recette->cout = $request['cout'];
        $recette->difficulte = $request['difficulte'];
        
        for($j = 1; $j <= $request->nbritt ; $j++){
            $nameinput = 'id'.$j ;
            $ingredient = Ingredient::find($request[$nameinput]);

            $ingredient->quantite = $request['quantite'.$j];
            $ingredient->produit_id = $request['produit'.$j];
            $ingredient->unite = $request['unite'.$j];
            $recette->ingredients()->save($ingredient);
        }
        
        //$key = $request->nbringrd - $request->nbritt;
       
        for($j = $request->nbritt +1 ; $j <= $request->nbringrd ; $j++){
            $ingredient = new Ingredient();
            $ingredient->quantite = $request['quantite'.$j];
            $ingredient->produit_id = $request['produit'.$j];
            $ingredient->unite = $request['unite'.$j];
            $recette->ingredients()->save($ingredient);
        }
        

        for ($i = 1; $i <= $request->nbrstep ; $i++) {
            $namestep = 'idstep'.$i ;
            $etape= Etape::find($request[$namestep]);
            $etape->description = $request['step'.$i];
            $recette->etapes()->save($etape);
        }

        for($j = $request->nbrstep +1 ; $j <= $request->nbr ; $j++){
            $etape = new Etape();
            $etape->description = $request['step'.$j];
            
           
            $recette->etapes()->save($etape);
        }

    	$recette->save();
    	return redirect('/recettes_cuisinier');    	
    }

     public function destroy($id)
    {
        $recette = Recette::find($id);
        
        $recette->delete();
 
        return redirect('recettes_admin');
    }

    public function destroy_recette($id)
    {
        $recette = Recette::find($id);
        
        $recette->delete();
 
        return redirect('recettes_cuisinier');
    }

    public function search(Request $request)
    {   

        if($request->select != 'nothing' && $request->keyword == null){
           
            $recettes = Recette::where('categorie_id', '=', $request->select )->get();
        }
        if($request->select != 'nothing' && $request->keyword != null){
            
            $recettes = Recette::where('titre','LIKE','%'.$request->keyword.'%')->where('categorie_id', '=', $request->select )->get();
        }
        if($request->select == 'nothing' && $request->keyword != null){
            $recettes = Recette::where('titre','LIKE','%'.$request->keyword.'%')->get();
        }
        if($request->select == 'nothing' && $request->keyword == null){
            $recettes = Recette::where('titre','LIKE','USA1547')->get();
            
        }

        
        
    return view('search_recipe',['recettes'=>$recettes]);

    }


  public function LesRecettes(){
      $recettes = Recette::all();
      $categories = Categorie::all();
      return view('LesRecettes',compact('recettes','categories'));
  }
  

  

 

}
