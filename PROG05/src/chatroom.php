<?php
require_once __DIR__ . "/bootstrap.php";

require_login();

$user_data = get_detail_from_id('user', $_SESSION['id']);
$list_user = get_all_table_db('user');
?>

<?php view('header', ['title' => 'Đoạn chat']) ?>
<div class="d-flex align-items-center h-100">
  <div class="container">
    <div class="row">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-5 col-xl-4" id="list-user">
              <div class="input-group rounded mb-3">
                <input type="search" class="form-control rounded" placeholder="Search" />
                <span class="input-group-text border-0">
                  <i class="bi bi-search"></i>
                </span>
              </div>
              <div class="overflow-y-scroll" style="height: 500px">

                <ul class="list-group list-group-flush">
                  <?php foreach ($list_user as $user): ?>
                    <?php
                    if ($user['id'] == $_SESSION['id']) {
                      continue;
                    }
                    ?>
                    <li
                      class="select-user p-2 list-group-item d-flex justify-content-start align-items-center list-group-item-action"
                      data-to-userid="<?= $user['id'] ?>" data-to-username="<?= $user['full_name'] ?>"
                      style="cursor: pointer">
                      <img src="<?= isset($user['avatar_path']) ? $user['avatar_path'] : '/imgs/default.png' ?>"
                        alt="avatar" width="40" style="object-fit: contain;">
                      <div class="pt-1 ms-3">
                        <p class="small mb-0 fw-bold">
                          <?= $user['full_name'] ?>
                        </p>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>

            <div class="col-lg-7 col-xl-8 overflow-y-scroll">
              <div id="chat_area">
              </div>
              <form method="POST" id="message_form" aria-disabled="">
                <fieldset disabled="disabled">
                  <div class="d-flex justify-content-start align-items-center my-1">
                    <input type="hidden" name="from_id" id="from_id" value="<?= $_SESSION['id'] ?>" />
                    <input type="text" class="form-control" id="message" placeholder="Type message" maxlength="1000"
                      required>
                    <button type="submit" class="btn btn-primary ms-3">
                      <i class="bi bi-send-fill"></i>
                    </button>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php view('footer') ?>

<script type="text/javascript">
  $().ready(() => {
    var conn = new WebSocket('ws://prog05.test:8080', 'echo-protocol');
    conn.onopen = function (event ) {
      alert("Connection established!");
    };

    conn.onmessage = function (event) {
      let data = JSON.parse(event.data);
    };

    conn.onclose = () => {
      console.log("Connection closed")
    }

    function make_chat_area(receiver_username) {
      let html = `
        <div class="container-fluid border-bottom border-2 px-0 d-flex justify-content-between align-items-center">
          <p class="fw-bold">${receiver_username}</p>
        </div>
      <div class="py-3" style="height: 500px">
        <div class="d-flex flex-row justify-content-start">
          <div>
            <small>Name</small>
            <p class="small p-2 mb-2 rounded-3" style="background-color: #f5f6f7;">
              Lorem ipsum dolor sit, amet consectetur adipisicing elit. Pariatur, nihil?
            </p>
          </div>
        </div>
        <div class="d-flex flex-row justify-content-end">
          <div>
            <p class="small p-2 mb-2 text-white rounded-3 bg-primary">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptates, veritatis.
            </p>
          </div>
        </div>
      </div>
      `
      $('#chat_area').html(html);
      $("fieldset").prop("disabled", false);
    }

    $('#list-user').on('click','.select-user', function () {
      receiver_userid = $(this).data('to-userid');
      let receiver_username = $(this).data('to-username');
      let from_user_id = $('#user_id').val();
      make_chat_area(receiver_username);
      $.ajax({
        url: "chataction.php",
        method: "POST",
        data: { action: 'fetch_chat', to_user_id: receiver_userid, from_user_id: from_user_id },
        dataType: "JSON"
      })
        .done(function (data) {
          if (data.length > 0) {
            console.log(data)
          }
        })
    })

    $('#message_form').on('submit', function (event) {
      event.preventDefault();
      let from_id = parseInt($('#from_id').val());
      let to_id = receiver_userid;
      let message = $('#message').val();
      var data = {
        from_id: from_id,
        to_id: to_id,
        message: message
      };
      conn.send(JSON.stringify(data));
      $('#message').val('');
    })
  }
  )

</script>