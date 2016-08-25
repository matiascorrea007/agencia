<?php

namespace agencia\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Request;
use agencia\Http\Requests;
use agencia\User;
use agencia\Producto;
use agencia\ProductosAdd;
use Redirect;
use Auth;
use DB;
use Cart;
use agencia\Transaction;
use agencia\Venta;
use agencia\Cliente;
use Alert;


class VentaController extends Controller
{

    public function __construct()
    {
        /*si no existe mi session cart , esntonces la creo con put y creo
        un array para almacenar los items*/
        if(!\Session::has('cart')) \Session::put('cart', array());
        //para cliente ya no es un array ya que almaceno 1 solo objeto
        if(!\Session::has('cliente')) \Session::put('cliente');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
/*---------------------------------carrito--------------------------------------*/
    public function addproducto(){
        //me busca los productos
        //$productos = producto::Paginate(8);
        //me los manda a productoadd asi los seleccioens
        //return View('admin.venta.productoadd')->with('productos',$productos);
    }

    //mostrar carrito
    public function show()
    {
        //carga los productos en el modal
        $productos = producto::Paginate(8);
        /*obtengo mi variable de session cart que cree y la almaceno en $cart */
        $cart = \Session::get('cart');
        /*obtengo mi variable de session cliente que cree y la almaceno en $cart */
        $cliente = \Session::get('cliente');
        //llama a la funcion total
        $total = $this->total();
        return view('admin.venta.index', compact('cart','total','cliente','productos'));
    }

    //agregar item
    public function add($id)
    {
        $itemadd  = producto::find($id);
        
        $cart = \Session::get('cart');
        $itemadd->quantity = 1;
        $cart[$itemadd->pro_descrip] = $itemadd;
        \Session::put('cart', $cart);
       
        return redirect('venta-show');

     }

     // Delete item y client
    public function delete($id)
    {
        $item  = producto::find($id);
        $cart = \Session::get('cart');
        unset($cart[$item->pro_descrip]);
        \Session::put('cart', $cart);

        return redirect('venta-show');
    }


     // Update item
    public function update($id, $quantity)
    {
        
        $item  = producto::find($id);
        $cart = \Session::get('cart');
        $cart[$item->pro_descrip]->quantity = $quantity;
        \Session::put('cart', $cart);

        return redirect('venta-show');
    }


    //limpiar carrito y cliente
     public function trash()
    {
        \Session::forget('cart');
        \Session::forget('cliente');
         Alert::success('Mensaje existoso', 'Venta Creada');
        return redirect('venta-show');
    }


    //total del carrito
    private function total()
    {
        $cart = \Session::get('cart');
        $total = 0;
        foreach($cart as $item){
            $total += $item->pro_venta * $item->quantity;
        }
        return $total;
    }


     public function checkout(Request $Request)
    {
        $total = $this->total();

        //traigo el tipo de pago y si es efectivo que se guarde como pagado en otro caso 
        //que se guarde como pendiente
        $tipo_pago=$Request['tipo_pago'];
        if ($tipo_pago == "Efectivo") 
        {
            $tipo_pago = "pagado";
        }else{  
            $tipo_pago="pendiente";            
        }
        //traigo el cliente de la session
        $cliente = \Session::get('cliente');
        //genero una venta que estara relacinada con los productos en las transacciones
        $venta = new Venta();
        $venta->cliente_id    = $cliente->id;
        $venta->user_id       = Auth::user()->id;
        $venta->pago_tipo     = $Request['tipo_pago'];
        $venta->total         = $total;
        //$venta->comentario  =
        $venta->status = $tipo_pago;
        $venta->save();

        //traigo todos los productos de la session  del usuario 
        $cart = \Session::get('cart');
        //los recorro
        foreach ($cart as $item) {
            //crea una nueva transaccion
            $transaction  = new Transaction();
            //alamacena la transaccion
            $transaction->venta_id    = $venta->id;
            $transaction->producto_id  = $item->id;
            $transaction->user        = Auth::user()->usu_nombre;
            $transaction->cantidad    = $item->quantity;
            $transaction->total_price = $item->pro_venta * $item->quantity;
            //guardo la transaccion
            $transaction->save();

            //descontar stock en la tabla producto
            $producto = producto::find($item->id);
            $producto->pro_stock_act = $producto->pro_stock_act - $item->quantity;
            $producto->save();
        }   

       
        //redirecciona para destruir el carrito de la seccion
         return Redirect::to('venta-trash');
    }
/*---------------------------------carrito--------------------------------------*/



 /*---------------------------------Listar Ventas--------------------------------------*/
    public function listarVenta(request $request){


         $ventas=venta::orderBy('id')->paginate(50);
         $transactions = transaction::all();

         /*buscador*/
        $fechai=$request->input('fecha_inicio');
        $fechaf=$request->input('fecha_final');
        if (!empty($fechai) and !empty($fechaf)) {
            //entonces me busque de usu_nombre a el nombre que le pasamos atraves de $usu_nombre
            $ventas = venta::where('created_at', '>=' , $fechai)->where('created_at', '<=', $fechaf)->paginate(50);
        }
        /*buscador*/

        return view('admin.venta.listar.index',compact('ventas','transactions'));
    }


public function detalleVentaPdf($tipo,$id){
        $vistaurl="admin.venta.venta-detalle-pdf";
        $ventas=venta::all();
        $transactions = transaction::all();
        
     return $this->crearPDF($ventas, $transactions , $vistaurl,$tipo,$id);
     
    }

    public function crearPDF($ventas, $transactions , $vistaurl,$tipo ,$id){
        $data = $ventas;
        $date = date('Y-m-d');
        $view =  \View::make($vistaurl, compact('data', 'date', 'transactions','id'))->render();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        
        if($tipo==1){return $pdf->stream('reporte');}
        if($tipo==2){return $pdf->download('reporte.pdf'); }
     
    }

    public function cambiarStatus(Request $Request , $id){
    

        $venta=venta::find($id);
        $venta->status=$Request['pago'];
        $venta->save();
        return Redirect::to('/listar-venta');

    }

  /*  public function detalleVenta($id){
        //$items = Transaction::with('product_id')->where('venta_id','=',$request->get('venta_id'))->get();
        //return json_encode($items);
      
        $ventas = venta::all();
         $ventas= venta::Paginate();
         $transactions = transaction::all();
        //$items=venta::find($id);
        $mycart = DB::table('transactions')->where('venta_id','=',$id)->get();
       
        //$myitemadds = DB::table('transactions')->where('venta_id','=',$id)->get();
       return view('admin.venta.listar.index')
        ->with('ventas',$ventas)
         ->with('transactions',$transactions)
       ->with('mycart',$mycart);
    }/*
/*---------------------------------Listar Ventas--------------------------------------*/






/*---------------------------------cliente--------------------------------------*/
public function seleccionarCliente(request $request)
    {
         //me busca los clientes
        $clientes = cliente::orderBy('clie_nombres');

        /*------------buscador-----------*/
        //lo que ingresamos en el buscador lo alamacenamos en $usu_nombre
        $clie_nombre=$request->input('clie_nombres');
        //preguntamos que si ($usu_nombre no es vacio
        if (!empty($clie_nombre)) {
            //entonces me busque de usu_nombre a el nombre que le pasamos atraves de $usu_nombre
            $clientes->where('clie_nombres','LIKE','%'.$clie_nombre.'%');
        }
         $clientes=$clientes->paginate(10);
         /*------------buscador-----------*/


        //me los manda a productoadd asi los seleccioens
        return View('admin.venta.clienteadd')->with('clientes',$clientes);

     }

 public function addCliente($id)
    {
        $clienteadd  = cliente::find($id);
        $cliente = \Session::get('cliente');
        $cliente = $clienteadd;
        \Session::put('cliente', $cliente);
         return redirect('venta-show');
     }


/*---------------------------------cliente--------------------------------------*/




/*---------------------------------vendedor--------------------------------------*/
/*---------------------------------vendedor--------------------------------------*/


}