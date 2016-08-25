@foreach($users as $user)
<div class="modal fade bs-example-modal-lg" id="ver-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
 <div class="modal-dialog modal-lg" role="document">
     <div class="modal-content">
         <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Ver User {{ $user->usu_nombre }}</h4>
         </div>


{!!Form::model($user,['route'=>['usuario.update',$user->id],'method'=>'PUT' , 'files'=>True])!!}

<div class="modal-body">
<fieldset disabled>      
{{ Html::image('storage/' . $user->path , 'img', array('class' => 'user-image' , 'style'=>'height:100px')) }}
@include('admin.usuario.forms.formscreate')
</fieldset>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Close</button>
{!!Form::close()!!}
</div>


     </div>
   </div>
 </div>
	@endforeach