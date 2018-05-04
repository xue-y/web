/* JavaScript Document
 	调用 ：函数（传参）
*/
 
/* 设置cookie 
	name---- cookie name
	value--- cookie value
	expires--- cookie Expiry time
 */
function setCookie(name,value,expires)
{
   var date=new Date();
   if(expires)
   {
	  date.setHours(date.getHours()+expires);
	  document.cookie=name+'='+value+';expires='+date.toGMTString();
	}
	else
	{
	 document.cookie=name+'='+value;	
	}	
}
// 另一种方式设置 cookie
/*function setCookie(c_name,value,expiredays)
{
var exdate=new Date()
exdate.setDate(exdate.getDate()+expiredays)
document.cookie=c_name+ "=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString())
}*/

/* 根据 cookie 名称 获取 cookie 的值
	name -----cookie name 
 */
function getCookie(name)
{
  	if(document.cookie.length>0)
	{
	   start=document.cookie.indexOf(name+'=');
	   if(start!=-1)
	   {
		 start=start+name.length+1;
		 end=document.cookie.indexOf(';',start);
		 if(end==-1)  
		 {
			 end=document.cookie.length;
		 }  
		 return document.cookie.substring(start,end);  
	   }	
	}
}

// 另一种方式获取 cookie
/*function getCookie(name)
{
  if(document.cookie.length>0)	
  {
	  var arr=document.cookie.split('; ');
      for(var i=0;i<arr.length;i++)
	  {
		var arr2=arr[i].split('=');
		  
		 if(arr2[0]==name)
		 {
			return arr2[1]; 
		 }
	  } 
 }
  else
  { return '';}
}*/

/* 清除 cookie
	cookie name
 */
function clearCookie(name)
{
  name_value=getCookie(name);
  if( name_value!=null && name_value!='')
  {
	  setCookie(name,'',-1);
  }
}

