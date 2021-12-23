function searchAndMark(what,where){

	if(/<[a-z][\s\S]*>/i.test(where)){
		return where;
	}
	if(where!=null && what!=null){
		var isFind=where.toLowerCase().indexOf(what.toLowerCase())
		if(isFind!=-1){
			var mark=where.substr(0,isFind);
			mark+="<span class=\"mark\">"
			mark+=where.substr(isFind, what.length).replace(' ', '&nbsp;');
			mark+="</span>"
			mark+=where.substr(isFind+what.length);
			return(mark);
		}else{
			return(where);
		}
	}else{
		return(where);
	}
}