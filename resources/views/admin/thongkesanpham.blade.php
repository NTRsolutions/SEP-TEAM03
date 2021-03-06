@extends('admin.master')
@section('title')
<title>Thêm Kê Sản Phẩm - CloudBooth</title>
@endsection
@section('content')
<div>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Danh Sách Sản Phẩm</h1>
        </div>
        <!-- /.col-lg-12 -->
        <div class="col-lg-12">
            @if(session('thongbao'))
            <div class="alert alert-success">
                {{session('thongbao')}}
            </div>
            @endif
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">

                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">

                    <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-thongkesp">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên</th>
                                <th></th>

                                <th>Gian hàng</th>
                                <th>Ngày đăng</th>

                                <th>Thao tác</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($Sanpham as $sp)
                            <tr class="odd gradeX">
                              <td><img width="100px" src="source/image/product/{{$sp->image}}"/></td>
                              <td><a href="{{ route('chitietsp',$sp->id) }}">{{$sp->name}}</a></td>

                              <td>{{ $sp->product_type()->first()->name }}</td>




                              <td>{{ $sp->supplier()->first()->shopname }}</td>
                              <td>{{ date('Y-m-d', strtotime($sp->created_at)) }}</td>



                              <td class="center">


                                @if($sp->unlock == 1)
                                <i class="fa fa-unlock fa-fw"></i>
                                <a style="color: green" href="{{ route('admin.product.lock',$sp->id) }}"  onclick="return confirm('Bạn có muốn Khóa sản phẩm này hay không?')">Đang Mở Khóa</a>
                                @else
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <a style="color: red" href="{{ route('admin.product.lock',$sp->id) }}"  onclick="return confirm('Bạn có muốn Mở Khóa sản phẩm này hay không?')">Đang Khóa</a>
                                @endif



                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
</div>
<script>
    $(document).ready(function() {

       var table = $('#dataTables-thongkesp').dataTable( {

        initComplete: function () {

            this.api().columns([2]).every( function () {
                var column = this;
                var select = $('<select><option value="">Loại Sản Phẩm</option></select>')
                .appendTo($(column.header()).empty())
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );
                    column

                    .search( val ? '^'+val+'$' : '', true, false  )
                    .draw();
                } );
                column.data().unique().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
   } );
</script>
@endsection
