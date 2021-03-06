@extends('layouts.app')
@section('content')

<!-- mostrar mensjae de validacion-->
@include('alerts.request')

<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Editar Usuarios</h3>
            </div>
			<div class="box-body">

<!-- $user es el elemento que estamos recibiendo y usuario.update hace referencia a la funcion update
de UsuarioController y el metodo PUT es para actualizar-->
{!!Form::model($user,['route'=>['usuario.update',$user->id],'method'=>'PUT' ,'files'=>'True'])!!}

{{ Html::image('storage/' . $user->path , 'img', array('class' => 'user-image')) }}

@include('admin.usuario.forms.formsedit')

{!!Form::submit('modificar',['class'=>'btn btn-primary'])!!}
{!!Form::close()!!}

			     </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>



@endsection