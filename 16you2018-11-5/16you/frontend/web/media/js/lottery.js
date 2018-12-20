var luck={//对整个页面初始化
			index:-1,	//当前转动到哪个位置，起点位置
			count:0,	//总共有多少个位置
			timer:0,	//setTimeout的ID，用clearTimeout清除
			speed:20,	//初始转动速度
			times:0,	//转动次数
			cycle:50,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
			prize:-1,	//中奖位置
			init:function(id){
				if ($("#"+id).find(".luck-unit").length>0) {
					$luck = $("#"+id);
					$units = $luck.find(".luck-unit");
					this.obj = $luck;
					this.count = $units.length;
					$luck.find(".luck-unit-"+this.index).addClass("active");
				};
			},			
			roll:function(){
				var index = this.index;
				var count = this.count;
				var luck = this.obj;
				$(luck).find(".luck-unit-"+index).removeClass("active"); 
				index += 1;
				if (index>count-1) {//从0开始转动
					index = 0;
				};
				$(luck).find(".luck-unit-"+index).addClass("active");
				this.index=index;
				return false;
			},
			stop:function(index){
				this.prize=index;
				return false;
			}
		};

		function roll(){
			luck.times += 1;
			luck.roll();//转动过程调用的是luck的roll方法，这里是第一次调用初始化 
			if (luck.times > luck.cycle+10 && luck.prize==luck.index) {
				clearTimeout(luck.timer);
				tips(luck.prize);//调用显示抽到的信息
				luck.prize=-1;
				luck.times=0;
				click=false;
			}else{
				if (luck.times<luck.cycle) {
					luck.speed -= 10;
				}else if(luck.times==luck.cycle) {
					//var index = Math.random()*(luck.count)|0;//静态演示，随机产生一个奖品序号，实际需请求接口产生
					//var index=8;
					//luck.prize = index;	
					$.ajax({
			    		url:'/rankinged/turnplateluck.html',
			      		type:'post',
			      		dataType:'json',
			      		success:function(data){
			      			if(data.errorcode == 0){
			      				luck.prize = data.info;
			      			}else{
			      				alert(data.info);
			      			}
			      		}
			    	});
				}else{
					if (luck.times > luck.cycle+10 && ((luck.prize==0 && luck.index==7) || luck.prize==luck.index+1)) {
						luck.speed += 110;
					}else{
						luck.speed += 20;
					}
				}
				if (luck.speed<40) {
					luck.speed=40;
				};

				luck.timer = setTimeout(roll,luck.speed); //循环调用
			}
			return false;
		}

		var click=false;
		window.onload=function(){
			luck.init('luck');
			$("#btn").click(function(){//click控制一次抽奖过程中不能重复点击抽奖按钮，后面的点击不响应
				var number = $('.lotterynumber').text();
				console.log(number);
				if(click) {
					return false;
				}else{
                   if(number >= 1){
                       	number -=1;
						$('.lotterynumber').text(number);
						luck.speed=100;
						roll();//转圈过程不响应click事件，会将click置为false
						click=true;//一次抽奖完成后，设置click为true，可继续抽奖	
                   }else if(number == '0'){
                   	  alert('您的抽奖次数已用完');
                   }
					return false;
				}
			});
		};

        function tips(num){//抽到奖品提示
            $('.rotatetips').show();
            var prizeimg = $('.prizeimg');
            var tips = $('.gettips');
            var moneybox = $('.moneybox');
            var money = $('.money');
            if(num=='0'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/red.png');
              	moneybox.show();
              	money.text('1.68');
              tips.text('获得红包1.68元,已放入你的账户');
            }else if(num=='1'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/1.png');
              tips.text('"恭"字一张，请在仓库查看');
            }else if(num=='2'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/hohX.png');
              tips.text('iPhoneX一部，请联系客服QQ:12324343兑换');
            }else if(num=='3'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/2.png');
              tips.text('"喜"字一张，请在仓库查看');
            }else if(num=='4'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/red.png');
              	moneybox.show();
              	money.text('5888');
              tips.text('获得红包5888元,已放入你的账户');
            }else if(num=='5'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/jqp.png');
              tips.text('获得京东福卡，请联系客服QQ:12324343兑换');
            }else if(num=='6'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/red.png');
            	moneybox.show();
            	money.text('118');
              tips.text('118元红包一个，已放入你的账户');
            }else if(num=='7'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/4.png');
              tips.text('"财"字一张，请在仓库查看');
            }else if(num=='8'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/red.png');
            	moneybox.show();
            	money.text('16.8');
              tips.text('16.8元红包一个，已放入你的账户');
            }else if(num=='9'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/3.png');
              tips.text('"发"字一张，请在仓库查看');
            }else if(num=='10'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/car.png');
              tips.text('小米平衡车，请联系客服QQ:12324343兑换');
            }else if(num=='11'){
            	prizeimg.attr('src',cdn_url+'/images/newyears/hoh1.png');
              tips.text('获得iPad mini4，请在仓库查看');
            }
          }
		//关闭提示
		$('.rotatetips').click(function(){
			$('.rotatetips').hide();
		})