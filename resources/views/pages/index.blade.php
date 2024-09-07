@extends('layouts.AdminLTE.index')

@section('icon_page', 'file')

@section('title', 'Pages')

@section('menu_pagina')	
		
	<li role="presentation">
		<a href="{{ route('page.create') }}" class="link_menu_page">
			<i class="fa fa-plus"></i> Add
		</a>								
	</li>

@endsection

@section('content')    
        
    <div class="box box-primary">
		<div class="box-body">
			<div class="row">
				<div class="col-md-12">	
					<div class="table-responsive">
						<table id="tabelapadrao" class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>			 
									<th>Title</th>			 
									<th>Content</th>
									<th>Image</th>
									<th>Status</th>			 
									<th class="text-center">Actions</th>			 
								</tr>
							</thead>
							<tbody>
								@foreach($pages as $page)
										<tr>
											<td>
												{{ $page->title }}
											</td>             
											<td>{{ strip_tags($page->content) }}</td>             
											<td>
												<img src="{{ asset('storage/' . $page->image) }}" width="32px" alt="Page Image">
											</td>             
											<td>{{ $page->status_coll }}</td>             
											<td class="text-center"> 

												 <a class="btn btn-default  btn-xs" href="{{ route('page.show', $page->id) }}" title="See {{ $page->name }}"><i class="fa fa-eye">   </i></a>
												 <a class="btn btn-warning  btn-xs" href="{{ route('page.edit', $page->id) }}" title="Edit {{ $page->name }}"><i class="fa fa-pencil"></i></a> 
												 <a class="btn btn-danger  btn-xs" href="#" title="Delete {{ $page->name}}" data-toggle="modal" data-target="#modal-delete-{{ $page->id }}"><i class="fa fa-trash"></i></a> 
											</td> 
										</tr>
										<div class="modal fade" id="modal-delete-{{ $page->id }}">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">×</span>
														</button>
														<h4 class="modal-title"><i class="fa fa-warning"></i> Caution!!</h4>
													</div>
													<div class="modal-body">
														<p>Do you really want to delete ({{ $page->name }}) ?</p>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
														<a href="{{ route('page.destroy', $page->id) }}"><button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Delete</button></a>
													</div>
												</div>
											</div>
										</div>
								@endforeach
							</tbody>
							<tfoot>
								<tr>		 
									<th>Title</th>			 
									<th>Content</th>
									<th>Image</th>
									<th>Status</th>			 
									<th class="text-center">Actions</th>			 
								</tr>
							</tfoot>
						</table>
					</div>
				</div>				
				<div class="col-md-12 text-center">
					{{ $pages->links() }}
				</div>
			</div>
		</div>
	</div>    

@endsection