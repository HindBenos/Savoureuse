@extends('layouts.dashboard-cuisinier')
@section('content')

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                
            </div>
          
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title">Vous trouverez ci-joint la table des recettes</h3>
                    <a class="btn btn-primary float-right" href="{{url('addrecette')}}">Ajouter</a>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">ID</th>
                                    <th class="border-top-0">Titre</th>
                                    
                                    <th class="border-top-0">Date</th>
                                    
                                    <th class="border-top-0">Statu</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recettes as $recette)
                               
                                    
                                
                                <tr>
                                    <td>{{ $recette->id }}</td>
                                    <td>{{ $recette->titre }}</td>
                                    
                                    <td>{{ $recette->created_at }}</td>
                                    
                                    
                                    <td >
                                        @if ($recette->accept == 0)
                                             <h5><span class="badge bg-warning text-dark">En attent</span></h5>
                                        @else  <h5><span class="badge bg-success">Approuvé</span></h5>
                                        @endif
                                           
                                          
                                      </td>
                                    
                                    <td>
                                    
                                        <form action="{{url('recettes_cuisinier/'.$recette->id)}}">
                                            {{csrf_field()}}
                                            {{method_field('DELETE')}}
                                            <a href="{{ url('recette/'.$recette->id) }}" class="btn btn-success ">
                                                <i class="fas fa-eye" style="color:#ffff;" > </i>
                                                </a>
                
                                            <a href="{{url('addrecette/'.$recette->id.'/edit')}}"  class="btn btn-warning  ">
                                                <i class="far fa-edit" style="color:#ffff;"> </i>
                                              </a>                                             
                                  
                                   
                                  
                                   
                                    <button type="submit" onclick="return confirm('Vous voulez vraiment supprimer?')" class="btn btn-danger ">
                                    <i class="fas fa-trash" style="color:#ffff;"> </i>

                                    
                                    
                                    </form>
                                    
                                    </td>
                                </tr>
                                
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End PAge Content -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Right sidebar -->
        <!-- ============================================================== -->
        <!-- .right-sidebar -->
        <!-- ============================================================== -->
        <!-- End Right sidebar -->
        <!-- ============================================================== -->
    </div>

@endsection