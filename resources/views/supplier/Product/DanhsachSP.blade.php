@extends('supplier.master')
@section('title')
<title>Danh Sách Sản Phẩm - CloudBooth</title>
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
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-danhsachsp">
                                <thead>
                                    <tr>
										<th>Hình ảnh</th>
                                        <th>Tên</th>
                                        <th>SKU</th>
                                        <th>Loại sản phẩm</th>
                                        <th>Số lượng</th>
										<th>Ngày đăng</th>
									    <th>Hiển thị</th>
										<th>Thao tác</th>
										
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php $count=1;?>
									@foreach($Sanpham as $sp)
										<tr class="odd gradeX">
											<td><img width="100px" src="source/image/product/{{$sp->image}}"/></td>
											<td><a href="{{ route('chitietsp',$sp->id) }}">{{$sp->name}}</a></td>
											<td>{{ $sp->SKU }}</td>
											<td>{{ $sp->product_type->name }}</td>
                                            
											
                                            <td>
                                                <p hidden="">{{ $sp->new }}</p>
                                                <input type="hidden" id="proId<?php echo $count; ?>" name="proId" value="{{ $sp->id }}" >
                                                <input type="number" class="form-control" id="qty<?php echo $count; ?>" name="qty" value="{{ $sp->new }}" style="width: 80px">

                                            </td>
											<td>{{date('Y-m-d', strtotime($sp->created_at))}}</td>
                                            @if($sp->active == 1)
                                            <td><span style="color: green">Có</span></td>
                                            @endif
                                            @if($sp->active == 0)
                                            <td><span style="color: red">Không</span></td>
                                            @endif
											<td class="center"><i class="fa fa-pencil fa-fw"></i>
                                                <a href="{{ route('supplier.product.edit',$sp->id) }}">Sửa</a> | 
                                                <a href="{{ route('supplier.product.showhide',$sp->id) }}">Ẩn/Hiện</a>
                                                
                                            </td>
											
										</tr>
                                        <?php $count++; ?>
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

   var table = $('#dataTables-danhsachsp').dataTable( {
        
        initComplete: function () {
            
            this.api().columns([3]).every( function () {
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
});
 </script>

<script type="text/javascript">
                $(document).ready(function(){
                    <?php for($i=1;$i<=$Sanpham->count();$i++){
                    ?>
                    $('#qty<?php echo $i; ?>').on('change keyup',function(){
                        var quantity = $('#qty<?php echo $i; ?>').val();
                        var id =  $('#proId<?php echo $i; ?>').val();
                        if(quantity <= 0) {
                            alert('Vui Lòng Nhập Số Lượng')
                            
                        }
                        else{
                            $.ajax({
                                type:'get',
                                dataType:'html',
                                url:'<?php echo url('/supplier/Product/Update-Quantity'); ?>/'+id,
                                data:"qty="+quantity,
                                success: function(respone){
                                    location.reload();
                                }

                            });
                        }


                    });
                    <?php
                        }
                    ?>
                });
            </script>
@endsection