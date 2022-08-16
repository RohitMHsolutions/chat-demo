<template>
    <div class="container mx-auto">
      <div class="min-w-full border rounded lg:grid lg:grid-cols-3">
        <div class="border-r border-gray-300 lg:col-span-1">
          <div class="mx-3 my-3">
            <div class="relative text-gray-600">
              <span class="absolute inset-y-0 left-0 flex items-center pl-2">
                <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  viewBox="0 0 24 24" class="w-6 h-6 text-gray-300">
                  <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </span>
              <input type="search" class="block w-full py-2 pl-10 bg-gray-100 rounded outline-none" name="search"
                placeholder="Search" required />
            </div>
          </div>

          <ul v-if="usersLoaded" class="overflow-auto h-[32rem]">
            <h2 class="my-2 mb-2 ml-2 text-lg text-gray-600">Chats</h2>
            <li @click = "loadMessage(user , false)" v-for="user in users">
            
              <a
                class="flex items-center px-3 py-2 text-sm transition duration-150 ease-in-out border-b border-gray-300 cursor-pointer hover:bg-gray-100 focus:outline-none">
                <img class="object-cover w-10 h-10 rounded-full"
                  src="https://picsum.photos/200"" alt="username" />
                <div class="w-full pb-2">
                  <div class="flex justify-between">
                    <span class="block ml-2 font-semibold text-gray-600">{{ user.name }}</span>
                    <span :id=" 'new_messages_' + user.id " v-if="user.new_messages" class="inline-block py-1 px-1.5 leading-none text-xs text-center whitespace-nowrap align-baseline font-bold bg-red-600 text-white rounded-full ml-2"> 
                        {{user.new_messages}}

                     </span>
                   
                  </div>
                  
                </div>
              </a>
            </li>
          </ul>
        </div>



        <div class="hidden lg:col-span-2 lg:block">
          <div class="w-full">
            <div class="relative flex items-center p-3 border-b border-gray-300">
               <img class="object-cover w-10 h-10 rounded-full"
                  src="https://picsum.photos/200"" alt="username" />

              <span class="block ml-2 font-bold text-gray-600">{{selected_user}}</span>
              <span class="absolute w-3 h-3 bg-green-600 rounded-full left-10 top-3">
              </span>
            </div>
            <div class="relative w-full p-6 overflow-y-auto h-[40rem]">
              <ul class="space-y-2">

                <li v-for="msg in all_msg" :class="[msg.justify , flex]">
                
                  <div  class="relative max-w-xl px-4 py-2 text-gray-700 rounded shadow">
                    <span class="block">{{msg.text}}</span>
                  </div>


                  <button @click = "deleteMessage(msg.id)" v-if="msg.is_delete" type="submit">
                   <i class="fas fa-trash"></i>
                 </button>
                </li>



                              
              </ul>

             
            </div>

            <div class="flex items-center justify-between w-full p-3 border-t border-gray-300">
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                </svg>
              </button>

              <input type="text" v-model="message" placeholder="Message"
                class="block w-full py-2 pl-4 mx-3 bg-gray-100 rounded-full outline-none focus:text-gray-700"
                name="message" required />
              <button>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
              </button>
              <button @click="sendMessage" type="submit">
                <svg class="w-5 h-5 text-gray-500 origin-center transform rotate-90" xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20" fill="currentColor">
                  <path
                    d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>
<script src="https://cdn.tailwindcss.com"></script>
<script>
    export default {
    props: [],
        data() {
            return {
                users: [],
                received_msg: [],
                sent_msg: [],
                selected_user:"",
                selected_user_id:"",
                message:"",
                all_msg:[],
                justify:"",
                timer:null,
                flex:"flex",
                usersLoaded:true,
                is_delete:"",
                body: 'Temporary Message'
            }
        },

        
        

        created() {
        var _this = this;
           $.ajax({
                  type: 'GET',
                  url: '/users',
                  success:function(data) {
                      _this.users = data;
                      _this.selected_user_id = data[0].id;
                      _this.selected_user = data[0].name;
                      _this. loadMessage(data[0]);
                      
                  }
              });
              setInterval(() => {
                _this.checkMessages()
              }, 3000)
             

        },

        methods: {
            sendMessage() {

                var _this = this;
                 $.ajax({
                    type: 'POST',
                    url: '/send-message',
                    data:{text:this.message , recipient_id:_this.selected_user_id},
                    
                    success:function(data) {
                  
                      _this.message = "";
                      _this.selected_user_id = _this.selected_user_id;
                      _this. loadMessage({id:_this.selected_user_id , name: _this.selected_user});
                    }
                });


               
            },

            loadMessage(user) {
              var _this = this;
             
                _this.selected_user_id = user.id;
                  _this.selected_user = user.name;
             
              
              var sender_id = user.id;
              
                $.ajax({
                    type: 'GET',
                    url: '/messages/' + _this.selected_user_id,
                    
                    success:function(data) {
                      
                    if(data != ""){
                    _this.received_msg = [];
                    _this.sent_msg = [];
                    _this.all_msg = [];
                        for(let j in data){
                          let message = data[j]
                          
                              if(data[j].sender_id == _this.selected_user_id){

                                message.justify = "justify-start";
                                message.is_delete = false;
                                  _this.all_msg.push(message);
                                 _this.is_delete = true;
                                  _this.justify = "justify-start";
                              }else{
                               message.justify = "justify-end";
                               message.is_delete = true;
                                _this.all_msg.push(message);
                                _this.is_delete = false;
                                _this.justify = "justify-end";
                              }
                          
                          _this.selected_user_id = user.id;
                      }
                    }else{
                      _this.all_msg = [];
                      _this.is_delete = false;
                    }
                    
                     $("#new_messages_" + _this.selected_user_id).html("");
                       _this.readMessage();
                    }
                });
            },

            readMessage(){
              var _this = this;
              
              $.ajax({
                    type: 'POST',
                    url: '/read-message/' + _this.selected_user_id,
                    data:{text:this.message , recipient_id:this.selected_user_id},
                    
                    success:function(data) {
            
                     
                    }
                });
            },

            checkMessages(){
            var _this = this;
            
           
                  $.ajax({
                    type: 'GET',
                    url: '/check-messages',
                    
                    success:function(data) {
                    
                    if(data.updates){
                   
                        for(let i in _this.users){
                          _this.users[i].new_messages = 0;
                         
                          for(let n in data.updates){
                              let update = data.updates[n];
                              if(update.sender_id == _this.users[i].id){
                                _this.users[i].new_messages = update.msg_count;
                              }
                          }

                        }
                        _this.usersLoaded = false;
                        _this.$nextTick(function(){
                            _this.usersLoaded = true;
                        });
                    }
                      _this. loadMessage({id:_this.selected_user_id , name: _this.selected_user});
                    }
                });
            },

             deleteMessage(id){
                $.ajax({
                    type: 'POST',
                    url: '/delete-message/' + id,
                    data: { _token : $('meta[name="csrf-token"]').attr('content')},
                    success:function(data) {
                    
                      
                      _this. loadMessage({id:_this.selected_user_id , name: _this.selected_user});
                    }
                });
             }

        }
    }
</script>


