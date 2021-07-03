$(function(){
//---------------------goods_type和nav的添加-------------------------

	$('.add').click(function(){
		$('#newrow').show();
	})
	$('#cancel').click(function(){
		$('#newrow input').val('');
		$('#newrow').hide();
	})
	$('#save').click(function(){
		let val1=$('#newsort').val();
		let val2=$('#newname').val();
		$('#cancel').click();
		$('#box_top').append('<div class="row">'+$('#box_top>.row:first').html()+'</div>');
		$('#box_top>.row:last-of-type .sort_text').val(val1);
		$('#box_top>.row:last-of-type .name').html(val2);
	})
//-------------------goods_add输入框的添加---------------------------
	$('.type_size a').click(function(){
		if(this.className=='icon-zengjia'){
			$div=$('<div>');
			let a=$($(this).parents('.add_box').find('div:first').html()).val('');
			$div.html(a);
			$(this).parent().before($div);
		}else if(this.className=='icon-jianshao'){
			if($(this).parents('.add_box').find('div').length>1){
				$(this).parents('.add_box').find('div:last').remove();
			}
		}
	})
//-------------------goods_add和banner_add图片点击显示---------------------------
	$('#right img').click(function(){
		$('#img_box>img').attr('src',this.src);
		$('#img_box').show();
	})
	$('#img_box>a').click(function(){
		$(this).parent().hide();
	})
//-------------------全选---------------------------
	$('#check_all').change(function () {
		$('.check').prop('checked',this.checked);
	})
})
//-------------------删除选中商品--------
function delall(url) {//url是数据提交的链接
	var obj=$('.check:checked');
	if(obj.length<1){
		alert("请先选择要删除的数据");
	}else if(confirm("确认删除选中的数据吗？")){
		let data=[];
		for (let v of obj){
			data[data.length]=v.value;
		}
		$.post(url,{data:data},function (data) {
			console.log(data);
			if (data>0){
				alert("删除成功");
			}else{
				alert(data);
			}
			location=location;
		})
	}
}
//----------------删除数据-----------------
function del(url,text) {
	// alert();
	if(confirm(text)){
		$.post(url,function (data) {
			if(data>0){
				location=location;
			}else{
				alert(data);
			}
		})
	}
}