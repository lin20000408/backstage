<script>
  const { newPicture: selectNewPicture } = document.form2;
  async function sendPictureData(e) {
    e.preventDefault();
    let isPass = true;
    // var myModal = new bootstrap.Modal(document.getElementById('changePictureModal'));
    // myModal.hide();

    //圖片是否有選擇

    if (selectNewPicture.value) {
      if (selectNewPicture.files.length <= 1) {
        //呼叫Imgur api
        Imgur = await uploadImage(selectNewPicture);
        console.log(Imgur);

        if (!Imgur) {
          isPass = false;
          selectNewPicture.style.border = "2px solid red";
          selectNewPicture.nextElementSibling.innerHTML = '檔案格式錯誤';
        }
      } else {
        selectNewPicture.style.border = "2px solid red";
        selectNewPicture.nextElementSibling.innerHTML = '最多一張圖片';
      }
    } else {
      Imgur = "<?= $row['picture'] ?>";
    }

    if (isPass) {
      const fd = new FormData(document.form2);
      fd.append("url", Imgur);
      //傳送
      fetch('../admin/admin-api/editPicture-api.php', {
        method: 'POST',
        body: fd
      })
        .then(r => r.json())
        .then(result => {
          console.log(result);
          if (result.success) {
            const modal = new bootstrap.Modal(document.getElementById('changePictureModal'));
            modal.hide()
            successModal.show();
          } else {

            if (result.error) {
              failureInfo.innerHTML = result.error;
            } else {
              failureInfo.innerHTML = '圖片新增失敗';
            }

            failureModal.show();
          }
        })
        .catch(ex => {
          console.log(ex);
          failureInfo.innerHTML = '圖片新增發生錯誤' + ex;
          failureModal.show();
        })

    }

    // 透過js控制資料新增成功/失敗視窗
    const successModal = new bootstrap.Modal('#successModal');
    const failureModal = new bootstrap.Modal('#failureModal');

    // 抓錯誤訊息
    const failureInfo = document.querySelector('#failureModal .alert-danger');


  }

  const myRows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;
  function deleteOne(id) {
    if (confirm(`是否要刪除編號為 ${id} 的項目?`)) {
      location.href = `delete-admin.php?id=${id}`;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const changePictureBtn = document.getElementById('changePictureBtn');
    const changePictureModal = new bootstrap.Modal(document.getElementById('changePictureModal'));

    // 點擊圖片時顯示模態對話框
    const loginPicture = document.getElementById('loginPicture');
    loginPicture.addEventListener('click', function () {
      changePictureModal.show();
    });

    // 點擊更改圖片按鈕時顯示模態對話框
    changePictureBtn.addEventListener('click', function () {
      changePictureModal.show();
    });
  });
</script>