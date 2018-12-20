//$(document).ready(function(){
	  /**
	   * ModalHelper helpers resolve the modal scrolling issue on mobile devices
	   * https://github.com/twbs/bootstrap/issues/15852
	   * requires document.scrollingElement polyfill https://github.com/yangg/scrolling-element
	   */
	  var ModalHelper = (function(bodyCls) {//弹框时禁止页面滚动
	    var scrollTop;
	    return {
	      afterOpen: function() {
	        scrollTop = document.scrollingElement.scrollTop;
	        document.body.classList.add(bodyCls);
	        document.body.style.top = -scrollTop + 'px';
	      },
	      beforeClose: function() {
	        document.body.classList.remove(bodyCls);
	        // scrollTop lost after set position:fixed, restore it back.
	        document.scrollingElement.scrollTop = scrollTop;
	      }
	    };
	  })('modal-open');
	  function openModal() {//打开模态框时
	    ModalHelper.afterOpen();
	  }
	  function closeModal() {//关闭模态框时
	    ModalHelper.beforeClose();
	  }
//})