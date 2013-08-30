var bookTemplate = ''
+ '<div class="book">'
+ '	<div class="book-image">'
+ '		<img src="{$img}" class="img-rounded">'
+ '	</div>'
+ '	<div class="book-info">'
+ '		<div>'
+ '			<a href="/book/detail/id/{$bookid}" class="ui-link" data-transition="none">{$title}</a>'
+ '		</div>'
+ '		<div>{$author}</div>'
+ '		<div>'
+ '			<em class="text-red">{$score}</em>分'
+ '			<small class="muted">({$numRaters}评)</small>'
+ '		</div>'
+ '	</div>'
+ '	<div class="clear"></div>'
+ '	<div>'
+ '		{$summary}'
+ '		<div>'
+	'			<a href="/book/detail/id/{$bookid}" class="ui-link" data-transition="none">查看详细</a>'
+	'			<small class="muted"> | </small> <a href="/note/index/bookid/{$bookid}" class="ui-link" data-transition="none">读书笔记</a>'
+	'			<small class="muted"> | </small> <a href="javascript:AppBook.collection({$bookid}, \'{$title}\');" class="ui-link" data-transition="none">收藏本书</a>'
+ '		</div>'
+ '</div>'
+ '</div>';

function _AppBook() {
	this.keyPrefix = 'book_';
	this.page = 1;
	this.listBusy = false;
	this.bookListAddress = '/book/list';

	this.loadInfo = function(msg) {
		$(".loading").children().html(msg);
	}
	
	// 显示列表
	this.showList = function(data) {
		var html = '';
		
		for(var i in data) {
			var tmp = bookTemplate;
			for(var k in data[i]) {
				var reg = new RegExp("\\{\\$"+k+"\\}", 'ig');
				tmp = tmp.replace(reg, data[i][k]);
			}
			html += tmp;
		}
		
		$("div.book-list").append(html);
	}
	
	// 获取列表
	this.getBookList = function() {
		if(this.listBusy) return;
		
		var _this = this;
		this.listBusy = true;
		
		_this.loadInfo('加载中...');
		this.ajaxRequest(
			this.bookListAddress,
			{page: this.page, timeline: this.getTimeline()},
			function(data) {
				_this.listBusy = false;
				
				if(data.error == 0) {
					_this.page = ++data.params.page;
					_this.setTimeline(data.params.timeline);
					_this.showList(data.msg);
					_this.loadInfo('点击加载更多...');
				} else {
					_this.loadInfo(data.msg);
				}
			},
			function() {
				_this.listBusy = false;
				_this.loadInfo('读取数据失败!');
			}
		);
	}
	
	// ajax request
	this.ajaxRequest = function(url, params, success, error) {
		var _this = this;
		
		$.ajax({
			url: url,
			data: params,
			dataType: "json",
			type: "post",
			success: (typeof success == "function" ? success : function(data) {
				if(data.error == 0) {
					_this[success](data.msg);
				} else {
					_this.showMessage(data.msg, true);
				}
			}),
			error: (typeof error == "function" ? error : function() {
				_this.showMessage('读取数据失败!', true);
			})
		});
	}
	
	this.getTimeline = function() {
		return LDB.item(this.keyPrefix+'timeline') || 0;
	}
	
	this.setTimeline = function(timeline) {
		timeline && LDB.set(this.keyPrefix+'timeline', timeline);
	}
	
	this.collection = function(bookid, bookTitle) {
		var k = this.keyPrefix+'collection';
		
		var list = LocalDB.item(k) || {};
		list[bookid] = bookTitle;
		LocalDB.set(k, list);
		
		alert('收藏成功!');
	}
	
	this.createCollectionList = function(template) {
		var books = LocalDB.item(this.keyPrefix+'collection') || {};
		var html = '';
		
		for(var k in books) {
			html += template.replace('{$bookid}', k).replace('{$title}', books[k]);
		}
		
		return html;
	}
}

// 本地 key-value 数据库
var LocalDatabase = function() {
	this.LS = null;
	
	this.sessionStorage = function() {
		return window.sessionStorage;
	}
	
	this.localStorage = function() {
		return window.localStorage;
	}
}

LocalDatabase.prototype.item = function(k) {
	var val = this.LS.getItem(k);
	if(val===null) return null;

	try{
		val = JSON.parse(val);
	} catch(e) {
		val = val;
	}

	return val;
};

LocalDatabase.prototype.set = function(k, val) {
	try{
		if(typeof(val) != 'string') val = JSON.stringify(val);

		this.LS.setItem(k, val);
	} catch(e) {
	}
};

LocalDatabase.prototype.list = function() {
	var k = '', list = {};

	for(var i = 0, l = this.LS.length; i < l; i++) {
		k = this.LS.key(i);
		list[k] = this.item(k);
	}

	return list;
};

LocalDatabase.prototype.clear = function() {
	this.LS.clear();
};

LocalDatabase.prototype.del = function(k) {
	this.LS.removeItem(k);
};

var LDB = new LocalDatabase();
LDB.LS = LDB.sessionStorage();

var LocalDB = new LocalDatabase();
LocalDB.LS = LocalDB.localStorage();

var AppBook = new _AppBook;