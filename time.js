// JavaScript Document
//js ��ȡʱ��
//format 2018-05-26 10:35:12
	function getFormatDate() {
        var now = new Date();  
          
        var year = now.getFullYear();       //��  
        var month = now.getMonth() + 1;     //��  
        var day = now.getDate();            //��  
          
        var hh = now.getHours();            //ʱ  
        var mm = now.getMinutes();          //��  
        var ss = now.getSeconds();           //��  
          
        var clock = year + "-";  
          
        if(month < 10)  
            clock += "0";  
          
        clock += month + "-";  
          
        if(day < 10)  
            clock += "0";  
              
        clock += day + " ";  
          
        if(hh < 10)  
            clock += "0";  
              
        clock += hh + ":";  
        if (mm < 10) clock += '0';   
        clock += mm + ":";   
           
        if (ss < 10) clock += '0';   
        clock += ss;   
        return(clock);   
    }
// no zero 2018-5-26 10:46:28
    function getNoZeroDate()
    {
        var now = new Date();  
          
        var year = now.getFullYear();       //��  
        var month = now.getMonth() + 1;     //��  
        var day = now.getDate();            //��  
          
        var hh = now.getHours();            //ʱ  
        var mm = now.getMinutes();          //��  
        var ss = now.getSeconds();           //��  
        return year+'-'+month+'-'+day+' '+hh+':'+mm+':'+ss;
    }