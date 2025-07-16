
<!-- Modern Chatbox Frontend (HTML + CSS + JS) -->
<style>
  #chatbox-container-modern {
    position: fixed;
    bottom: 32px;
    right: 32px;
    width: 520px;
    height: 600px;
    max-width: 98vw;
    max-height: 98vh;
    z-index: 9999;
    font-family: 'Segoe UI', Arial, sans-serif;
    box-shadow: 0 12px 40px rgba(0,0,0,0.22);
    border-radius: 22px;
    background: #fff;
    animation: fadeInUp 0.7s;
    display: flex;
    flex-direction: column;
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
    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="AD" />
    Live Chat Admin INDOBYPASS
    <div style="margin-left:auto;display:flex;gap:6px;">
      <!-- <button id="chatbox-minimize" title="Minimize" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;line-height:1;padding:0 4px;">&#8211;</button> -->
      <button id="chatbox-close" title="Close" style="background:none;border:none;color:#fff;font-size:18px;cursor:pointer;line-height:1;padding:0 4px;">&times;</button>
    </div>
  </div>
  <div id="chat-messages-modern"></div>
  <form id="chat-form-modern">
    <input type="text" id="chat-input-modern" placeholder="Ketik pesan..." autocomplete="off" />
    <button type="submit" id="chat-send-modern">Kirim</button>
  </form>
</div>
<button id="chatbox-open" style="display:none;position:fixed;bottom:24px;right:24px;z-index:9998;background:linear-gradient(90deg,#177dff 60%,#6a82fb 100%);color:#fff;border:none;border-radius:50%;width:56px;height:56px;box-shadow:0 4px 16px rgba(23,125,255,0.18);font-size:28px;cursor:pointer;align-items:center;justify-content:center;transition:background 0.2s;">
  <span style="font-size:28px;">💬</span>
</button>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
  // Dummy messages for demo
  var messages = [
    {user:'admin',text:'Halo, ada yang bisa dibantu?'},
  ];
  function renderMessages() {
    $('#chat-messages-modern').html(messages.map(function(m){
      var avatar = m.user==='admin' 
        ? '<span class="avatar"><img src="https://randomuser.me/api/portraits/men/32.jpg" style="width:100%;height:100%;border-radius:50%"></span>' 
        : '<span class="avatar"><img src="https://randomuser.me/api/portraits/women/44.jpg" style="width:100%;height:100%;border-radius:50%"></span>';
      var bubble = '<span class="bubble">'+$('<div>').text(m.text).html()+'</span>';
      return '<div class="chat-msg-modern '+(m.user==='admin'?'admin':'user')+'">'+avatar+bubble+'</div>';
    }).join(''));
    $('#chat-messages-modern').scrollTop($('#chat-messages-modern')[0].scrollHeight);
  }
  renderMessages();
  $('#chat-form-modern').on('submit',function(e){
    e.preventDefault();
    var val = $('#chat-input-modern').val();
    if(val.trim()=='') return;
    messages.push({user:'user',text:val});
    renderMessages();
    $('#chat-input-modern').val('');
    // Kirim ke webhook n8n
    $.ajax({
      url: 'https://n8n.indobypass.cloud/webhook/chatbot-receiver',
      method: 'POST',
      contentType: 'application/json',
      data: JSON.stringify({message: val, from: 'user'}),
      success: function(res) {
        // Jika ingin menampilkan balasan dari n8n, bisa tambahkan di sini
        if(res && res.reply) {
          messages.push({user:'admin',text:res.reply});
          renderMessages();
        } else {
          // polling untuk jawaban dari n8n
          pollN8nReply();
        }
      },
      error: function() {
        // Optional: tampilkan error jika gagal
      }
    });

    // Fungsi polling jawaban dari n8n
    function pollN8nReply() {
      $.ajax({
        url: 'https://n8n.indobypass.cloud/webhook/chatbot-receiver',
        method: 'GET',
        dataType: 'json',
        success: function(res) {
          if(res && res.reply) {
            messages.push({user:'admin',text:res.reply});
            renderMessages();
          }
        },
        error: function() {
          // Optional: tampilkan error jika gagal
        }
      });
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
  
});
</script>
