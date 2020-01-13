/**
 * 定义一个列表数据结构
 * 作用：添加元素、删除元素、清除所有元素，将数据中的元素组装成对象返回一个数据
 * @constructor
 */
function List() {
  this.dataStore = new Array();
  this.listSize = 0;
  this.pos = 0;
}
List.prototype = {
  constructor: List,
  append: function(name) {
    this.dataStore[this.listSize++] = name;
  },
  front: function() {
    this.pos = 0;
  },
  end: function() {
    this.pos = this.listSize - 1;
  },
  length: function() {
    return this.listSize;
  },
  find:function(name){
  	var index = -1;
  	this.dataStore.forEach(function(data, i, array){
  		if(data == name) {
  			index = i;
  		}
  	});
  	return index;
  },
  remove: function(name) {

    var index = this.find(name);
    if(index > -1) {
    	this.dataStore.splice(index,1);
    	--this.listSize;
    	return true;
    }

    return false;


  },
  getElement: function() {
  	return this.dataStore[this.pos];
  },
  clear: function() {
  	delete this.dataStore;
    this.dataStore = [];
    this.pos = this.listSize = 0;
  },
  getAllElement: function() {
    return this.dataStore.map(function(name, i, array) {
      return {
        id: i,
        name: name,
        html: '<div class="tag-checked-name">' + name.substr(0, 10) + '<em data-word-tag-close="' + name + '"></em></div>'
      }
    })
  }
}

/**
 * 定义一个用来对列表操作的对象
 * @param options
 */
var doKeyWord = function(options) {

  var settings = options;

  var list = new List();

  return AOP.mixin({
    init: function(arr) {
    	var that = this;
      // 初始化
      if (typeof arr == "string") {
        arr = arr == '' ? [] : arr.split(',');
      }
      if (typeof arr == 'undefined') {
        arr = [];
      }
      // 清空数据
      list.clear();
      // 便利添加数据中
      arr.forEach(function(data, i, array){
      	that.add(data);
      })
    },
    render: function() {
      // 渲染效果

      var valueArr = [];
      var array = list.getAllElement();
      var html = array.map(function(data, index) {
        valueArr.push(data.name);
        return data.html;
      });

      $(settings.panel).html(html.join(''));
      $(settings.value).val(valueArr.join(','));

    },
    add: function(name) {
      // 添加数据
      if(list.find(name) > -1) {
      	return false;
      }
      list.append(name);
    },
    clear: function() {
      list.clear();
    },
    front: function() {
      return list.front();
    },
    end: function() {
      return list.end()
    },
    getElement: function(){
    	return list.getElement();
    },
    remove: function(index) {
      list.remove(index);
    },
    length: function() {
      return list.length();
    }
  });
}

$(function() {

  $.fn.keyWord = function(options) {
    var keyWord = doKeyWord(options);
    // 对添加的数据进行检查
    function doCheck() {
      //console.log("aaa:"+options.max + "  " + keyWord.length());
      if (options.max < keyWord.length() + 1) {
        alert(options.tips);
        return false;
      }
      return true;
    }


    var render = keyWord.render;

    // 添加前检查
    keyWord.before('add', doCheck);
    // 初始化后渲染效果
    keyWord.after('init', render);
    // 添加后渲染效果
    keyWord.after('add', render);
    // 删除后渲染效果
    keyWord.after('remove', render);

    var that = $(this);
    // 删除元素
    $(document).on('click', '[data-word-tag-close]', function() {
      var id = $(this).data('word-tag-close');
      // 过滤掉不删除的
      keyWord.remove(id);
      console.log("id:",id);
    });
    /**
     * Backspace删除 对应的键盘编码
     * e.keyCode == 8 ：Backspace键
     */
    that.keydown(function(e) {
      var that = $(this);
      var val = $.trim(that.val());
      if (val == "" && e.keyCode == 8) {
      	keyWord.end();
        keyWord.remove(keyWord.getElement());
      }
    });

    // 添加数据
    function doAdd(name) {
      name = $.trim(that.val());
      that.val('');
      if (name == '') {
        return;
      }
      keyWord.add(name);
    }

    /**
     * 判断有输入空格吗
     * e.keyCode == 32 空格键
     */
    that.keyup(function(e) {
      var that = $(this);
      var isSpaceKey = /\s+$/gi.test(that.val());
      // 是空格键输入了一个空格字符
      if (e.keyCode == 32 && isSpaceKey) {
        doAdd(that.val())
      }
    });
    // 鼠标失去焦点
    that.blur(function(e) {
      doAdd(that.val())
    });

    this.init = function(arr) {
      keyWord.init(arr);
    }
    return this;

  }
});