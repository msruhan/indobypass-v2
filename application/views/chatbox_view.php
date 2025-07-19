<!-- Modern Chatbox Frontend (HTML + CSS + JS) -->
<style>
  /* Card style for chatbox */
  #chatbox-container-modern {
    position: static !important;
    margin: 0 auto;
    width: 100%;
    height: 400px;
    box-shadow: 0 8px 32px rgba(23,125,255,0.10);
    border-radius: 22px;
    background: #fff;
    animation: none;
    display: flex;
    flex-direction: column;
    z-index: 1;
    /* Responsive: gunakan lebar penuh parent */
    max-width: none;
  }
  .card-chatbox {
    width: 100%;
    margin: 0 auto 32px auto;
    box-shadow: 0 8px 32px rgba(23,125,255,0.10);
    border-radius: 22px;
    overflow: hidden;
    background: #fff;
    max-width: none;
  }
  #chatbox-open, #chatbox-close {
    display: none !important;
  }
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .chatbox-header-modern {
    background: linear-gradient(90deg,#177dff 60%,#6a82fb 100%);
    color: #fff;
    padding: 16px 18px 12px 18px;
    border-radius: 18px 18px 0 0;
    font-weight: 600;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .chatbox-header-modern img {
    width: 32px; height: 32px; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  #chat-messages-modern {
    flex: 1 1 auto;
    min-height: 0;
    max-height: 400px;
    overflow-y: auto;
    background: #f4f7fa;
    padding: 24px 18px;
    border-bottom: 1px solid #e3e6ea;
    font-size: 1.25rem;
    transition: background 0.2s;
  }
  .chat-msg-modern {
    display: flex;
    align-items: flex-end;
    margin-bottom: 12px;
    gap: 8px;
  }
  .chat-msg-modern.user {
    flex-direction: row-reverse;
  }
  .chat-msg-modern .avatar {
    width: 28px; height: 28px; border-radius: 50%; background: #e3e6ea;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: #177dff;
  }
  .chat-msg-modern .bubble {
    max-width: 70%;
    padding: 12px 18px;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    word-break: break-word;
    position: relative;
    font-size: 1.18rem;
  }
  .chat-msg-modern.user .bubble {
    background: linear-gradient(90deg,#177dff 60%,#6a82fb 100%);
    color: #fff;
    border-bottom-right-radius: 4px;
  }
  .chat-msg-modern.admin .bubble {
    border-bottom-left-radius: 4px;
  }
  #chat-form-modern {
    display: flex;
    gap: 12px;
    padding: 18px 24px;
    background: #fff;
    border-radius: 0 0 22px 22px;
    border-top: 1px solid #e3e6ea;
  }
  #chat-input-modern {
    flex: 1;
    padding: 12px 16px;
    border-radius: 8px;
    border: 1px solid #e3e6ea;
    outline: none;
    font-size: 1.18rem;
    transition: border 0.2s;
  }
  #chat-input-modern:focus {
    border-color: #177dff;
  }
  #chat-send-modern {
    background: linear-gradient(90deg,#177dff 60%,#6a82fb 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 0 24px;
    font-size: 1.18rem;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(23,125,255,0.08);
    transition: background 0.2s;
  }
  #chat-send-modern:hover {
    background: linear-gradient(90deg,#6a82fb 60%,#177dff 100%);
  }
</style>

<div id="chatbox-container-modern">
  <div class="chatbox-header-modern">
    <img src="<?= base_url() ?>assets/assets_members/img/chatbot.png" alt="AI Robot" class="avatar-img rounded-circle" />
    AI Agent INDOBYPASS
  </div>
  <div id="chat-messages-modern"></div>
  <form id="chat-form-modern" style="position:relative;">
    <input type="text" id="chat-input-modern" placeholder="Silahkan bertanya pada AI Agent.." autocomplete="off" />
    <button type="button" id="chat-emoji-btn" style="background:none;border:none;font-size:1.5em;cursor:pointer;margin-right:8px;">😀</button>
    <button type="submit" id="chat-send-modern">Kirim</button>
    <div id="chat-emoji-popup" style="display:none;position:absolute;bottom:56px;left:0;z-index:10000;background:#fff;border-radius:12px;box-shadow:0 4px 24px rgba(23,125,255,0.12);padding:12px 16px;max-width:340px;max-height:180px;overflow-y:auto;">
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😊</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😂</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😍</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">👍</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🙏</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🔥</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🥳</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😎</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😢</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🤔</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😡</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🥰</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😆</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😇</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🤩</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😜</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😋</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">😴</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🤗</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🙌</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">💪</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">🎉</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">❤️</span>
        <span class="emoji-item" style="font-size:1.6em;cursor:pointer;">💯</span>
      </div>
    </div>
  </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  // Load messages dari localStorage jika ada, jika tidak pakai default
  var messages = [];
  try {
    var saved = localStorage.getItem('chatbox_messages');
    if(saved) {
      messages = JSON.parse(saved);
    } else {
      messages = [
        {user:'admin',text:'Halo, ada yang bisa dibantu?'},
      ];
    }
  } catch(e) {
    messages = [
      {user:'admin',text:'Halo, ada yang bisa dibantu?'},
    ];
  }

  function saveMessages() {
    localStorage.setItem('chatbox_messages', JSON.stringify(messages));
  }

  function renderMessages(typing) {
    var html = messages.map(function(m){
      var avatar = m.user==='admin' 
        ? '<span class="avatar"><img src="'+window.location.origin+'/indobypass/assets/assets_members/img/chatbot.png" alt="AI Robot" class="avatar-img rounded-circle" style="width:100%;height:100%;border-radius:50%"></span>' 
        : '<span class="avatar"><img src="'+window.location.origin+'/indobypass/assets/assets_members/img/profile.jpg" alt="AI Robot" class="avatar-img rounded-circle" style="width:100%;height:100%;border-radius:50%"></span>' ;
      // Izinkan emoticon dengan innerHTML, tapi tetap escape script
      var safeText = m.text.replace(/</g,'&lt;').replace(/>/g,'&gt;');
      var bubble = '<span class="bubble">'+safeText+'</span>';
      return '<div class="chat-msg-modern '+(m.user==='admin'?'admin':'user')+'">'+avatar+bubble+'</div>';
    }).join('');
    if(typing) {
      html += '<div class="chat-msg-modern admin">'
        +'<span class="avatar"><img src="'+window.location.origin+'/indobypass/assets/assets_members/img/chatbot.png" alt="AI Robot" class="avatar-img rounded-circle" style="width:100%;height:100%;border-radius:50%"></span>'
        +'<span class="bubble"><span class="typing-dots"><span>.</span><span>.</span><span>.</span></span></span>'
        +'</div>';
    }
    $('#chat-messages-modern').html(html);
    $('#chat-messages-modern').scrollTop($('#chat-messages-modern')[0].scrollHeight);
  }
  renderMessages();

  $('#chat-form-modern').on('submit',function(e){
    e.preventDefault();
    var val = $('#chat-input-modern').val();
    if(val.trim()=='') return;
    messages.push({user:'user',text:val});
    saveMessages();
    renderMessages(true); // tampilkan animasi typing
    $('#chat-input-modern').val('');
    // Kirim ke webhook n8n
    $.ajax({
      url: 'https://n8n.indobypass.cloud/webhook/chatbot-receiver',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({message: val, from: 'user'}),
      success: function(res) {
        if(res && res.reply) {
          messages.push({user:'admin',text:res.reply});
          saveMessages();
          renderMessages();
        } else {
          pollN8nReply();
        }
      },
      error: function() {
        renderMessages(); // hilangkan typing jika error
      }
    });

    // Fungsi polling jawaban dari n8n
    function pollN8nReply() {
      renderMessages(true); // tetap tampilkan typing selama polling
      $.ajax({
        url: 'https://n8n.indobypass.cloud/webhook/chatbot-receiver',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if(res && res.reply) {
            messages.push({user:'admin',text:res.reply});
            saveMessages();
            renderMessages();
          } else {
            setTimeout(pollN8nReply, 1200); // polling ulang jika belum ada balasan
          }
        },
        error: function() {
          renderMessages(); // hilangkan typing jika error
        }
      });
    }
  });

  // Emoji popup logic
  $('#chat-emoji-btn').on('click',function(e){
    e.preventDefault();
    $('#chat-emoji-popup').toggle();
  });
  $(document).on('click','.emoji-item',function(){
    var emoji = $(this).text();
    var $input = $('#chat-input-modern');
    $input.val($input.val()+emoji);
    $('#chat-emoji-popup').hide();
    $input.focus();
  });
  // Hide popup if click outside
  $(document).on('mousedown',function(e){
    var $popup = $('#chat-emoji-popup');
    var $btn = $('#chat-emoji-btn');
    if(!$popup.is(e.target) && $popup.has(e.target).length===0 && !$btn.is(e.target)){
      $popup.hide();
    }
  });

  // Minimize & Close button logic
  $('#chatbox-minimize').on('click', function(){
    var $content = $('#chatbox-content-modern');
    if($content.is(':visible')) {
      $content.slideUp(200);
      $(this).html('&#x25B2;'); // up arrow
      $(this).attr('title','Open');
    } else {
      $content.slideDown(200);
      $(this).html('&#8211;'); // minimize
      $(this).attr('title','Minimize');
    }
  });
  $('#chatbox-close').on('click', function(){
    $('#chatbox-container-modern').fadeOut(200);
    $('#chatbox-open').fadeIn(200);
  });
  $('#chatbox-open').on('click', function(){
    $('#chatbox-container-modern').fadeIn(200);
    $(this).fadeOut(200);
  });

  // Hapus riwayat chat saat user logout
  window.clearChatboxHistory = function() {
    localStorage.removeItem('chatbox_messages');
    messages = [
      {user:'admin',text:'Halo, ada yang bisa dibantu?'},
    ];
    renderMessages();
  }
});
</script>
<style>
.typing-dots {
  display: inline-block;
  min-width: 32px;
}
.typing-dots span {
  display: inline-block;
  font-size: 1.5em;
  color: #177dff;
  animation: blink 1.2s infinite;
  margin-right: 2px;
}
.typing-dots span:nth-child(2) {
  animation-delay: 0.2s;
}
.typing-dots span:nth-child(3) {
  animation-delay: 0.4s;
}
@keyframes blink {
  0%, 80%, 100% { opacity: 0.2; }
  40% { opacity: 1; }
}
</style>

