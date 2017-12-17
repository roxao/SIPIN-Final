function runCompleteCallBack(_func){
	if(document.readyState === 'complete'){
		if(typeof _func == 'function'){
			return _func();
		}
	}
	setTimeout(function(){
		runCompleteCallBack(_func);
	}, 1000);
}