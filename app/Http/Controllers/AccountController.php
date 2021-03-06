<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\customer;
use App\User;
use App\address;
use App\quan_huyen;
use App\tinh_tp;
use App\xa_phuong;
use App\bill;
use App\aboutUs;
use Hash;
use Auth;

class AccountController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:customer');
	}
	public function getProfile(){
		$id = Auth::guard('customer')->user()->id;
		$address = address::where('id_customer','=',$id)->first();
		
		return view('account.pages.thongtinprofile',compact('address'));
		
		
	}

	public function getIndexProfile(){
		
		return view('account.pages.thongtincanhan');
		
		
	}

	public function getEditProfile(){
		
		
		return view('account.pages.chinhsuaprofile');
		
		
	}
	public function postEditProfile(Request $req){
		$this->validate($req,
			[
				'txtName'=>'required|min:6|max:50',
				'txtPhone'=>'min:8|max:12'

			],
			[
				'txtName.required'=>'Vui Lòng Nhập Tên',
				'txtName.min'=>'Họ Tên phải có đọ dài từ 6-30 ký tự',
				'txtName.max'=>'Họ Tên phải có đọ dài từ 6-30 ký tự',
				'txtPhone.min'=>'Số điện thoại phải có đọ dài từ 8-12 ký tự',
				'txtPhone.max'=>'Số điện thoại phải có đọ dài từ 8-12 ký tự',
			]

		);


		$id = Auth::guard('customer')->user()->id;
		$profile = Customer::find($id);
		$profile->name = $req->txtName;
		$profile->phone = $req->txtPhone;
		$profile->birth_date  = $req->txtBd;
		$profile->gender = $req->get('Gender',0); //xu ly radio button
		
		$profile->save();
		return redirect()->route('user.profile.index')->with('thanhcong','Cập nhật tài khoản thành công');;
	}

	public function getChangePassword(){

		
		return view('account.pages.doimatkhau');
		
	}

	public function postChangePassword(Request $req){
		$this->validate($req,
			[
				'txtCurrentPwd'=>'required',
				'txtNewPwd'=>'required|min:6|max:30|alpha_num',
				'txtConfirmPwd'=>'required|same:txtNewPwd'

			],
			[
				'txtCurrentPwd.required'=>'Vui Lòng Nhập mật khẩu hiện tại',
				'txtNewPwd.required'=>'Vui Lòng Nhập mật khẩu mới',
				'txtNewPwd.min'=>'Mật khẩu mới phải có độ dài từ 6 - 30 ký tự',
				'txtNewPwd.max'=>'Mật khẩu mới phải có độ dài từ 6 - 30 ký tự',
				'txtNewPwd.alpha_num'=>'Mật khẩu mới chỉ được chứa ký tự hoặc số',
				'txtConfirmPwd.required'=>'Vui Lòng Nhập Vào Ô Nhập lại mật khẩu',
				'txtConfirmPwd.same'=>'Mật khẩu nhập lại không đúng'

			]

		);

		$currentpwd = Auth::guard('customer')->user()->password;
          if(Hash::check($req->txtCurrentPwd,$currentpwd)){
               $customer = customer::find(Auth::guard('customer')->user()->id);
               $customer->password = Hash::make($req->txtNewPwd);
               $customer->save();
               return redirect()->back()->with('thanhcong','Thay đổi mật khẩu thành công');
          }
          else{
               return redirect()->back()->with('thatbai','Mật khẩu hiện tại không đúng');
          }

	}

	public function getAddressList(){
		$id = Auth::guard('customer')->user()->id;
		$address = address::where('id_customer','=',$id)->get();
		return view('account.pages.sodiachi',compact('address'));

	}

	public function getEditAddressList($id){
		$tinh_tp = tinh_tp::all();
		$address = address::find($id);
		return view('account.pages.chinhsuadiachi',compact('address','tinh_tp'));
		
	}
	public function postEditAddressList(Request $req,$id)
	{
		$this->validate($req,
			[
				'name'=>'required|min:6|max:50',
				'address'=>'required|min:6|max:100',
				'phone'=>'required|min:8|max:12'
			],
			[
				'name.required'=>'Vui lòng nhập tên',
				'address.required'=>'Vui Lòng Nhập Địa Chỉ',
				'phone.required'=>'Vui Lòng Nhập Số Điện THoại',
				'phone.min'=>'Số điện thoại phải có độ dài từ 8 - 12 ký tự',
				'phone.max'=>'Số điện thoại phải có độ dài từ 8 - 12 ký tự',
				'name.min'=>'Họ tên phải có độ dài từ 6 - 50 ký tự',
				'name.max'=>'Họ tên phải có độ dài từ 6 - 50 ký tự',
				'address.min'=>'Địa chỉ phải có độ dài từ 6 - 100 ký tự',
				'address.max'=>'Địa chỉ phải có độ dài từ 6 - 100 ký tự',
			]

		);
		
		$address = address::find($id); //tìm vị trí dòng có id = id getEditAddressList là dc khỏi tìm id user
		$address->name = $req->name;
		$address->addressde = $req->address;
		$xa_phuong = xa_phuong::where('code',$req->xa_phuong)->first();
		$address->mavung = $xa_phuong->path_with_type;
		$address->phone = $req->phone;
		$address->save();
		return redirect()->route('user.address')->with('thanhcong','Lưu Thành Công');;
	}
	public function getAddAddressList()
	{
		$tinh_tp = tinh_tp::all();
		return view('account.pages.themdiachipage',compact('tinh_tp'));
	}
	public function postaddAddressList(Request $req)
	{
		$this->validate($req,[
			'name'=>'required|min:6|max:50',
			'address'=>'required|min:6|max:100',
			'phone'=>'required|min:8|max:12'
		],
		[
			'name.required'=>'Vui lòng nhập tên',
			'address.required'=>'Vui Lòng Nhập Địa Chỉ',
			'phone.required'=>'Vui Lòng Nhập Số Điện THoại',
			'name.min'=>'Họ tên phải có độ dài từ 6 - 50 ký tự',
			'name.max'=>'Họ tên phải có độ dài từ 6 - 50 ký tự',
			'address.min'=>'Địa chỉ phải có độ dài từ 6 - 100 ký tự',
			'address.max'=>'Địa chỉ phải có độ dài từ 6 - 100 ký tự',
			'phone.min'=>'Số điện thoại phải có độ dài từ 8 - 12 ký tự',
			'phone.max'=>'Số điện thoại phải có độ dài từ 8 - 12 ký tự',
		]
	);
		$address = new address;
		$address->name = $req->name;
		$address->addressde = $req->address;
		$xa_phuong = xa_phuong::where('code',$req->xa_phuong)->first();
		$address->mavung = $xa_phuong->path_with_type;
		$address->phone = $req->phone;
		$address->id_customer = Auth::guard('customer')->user()->id;
		$address->save();
		return redirect()->back();


		
	}
	public function getOrders(){
		$orders= bill::where('id_user',Auth::guard('customer')->user()->id)->orderBy('created_at', 'decs')->get();
		
		return view('account.pages.donhangcuatoi',compact('orders'));
	}
	public function getOrdersDetail($id){
		$order = bill::find($id);
		return view('account.pages.orderdetail',compact('order'));
	}


}

