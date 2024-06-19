async function uploadImage(formFileEl) {
    const files = formFileEl.files;
    let hasErrorType = false;
    let links = [];
  
    //檢查檔案格式是否為圖片
    for (let file of files) {
      if (file.type.split("/")[0] !== "image") {
        hasErrorType = true;
        return;
      }
    }
    // 格式不符
    if (hasErrorType === true) {
      return false;
    }
    // 打api到Imgur
    for (let file of files) {
      
      //建立header
      const myHeaders = new Headers();
      myHeaders.append("token", "ddshop");
      
      //建立body
      const formdata = new FormData();
      formdata.append("file", file);
      
      //打api
      const response = await fetch(
        "https://yne.myds.me:13000/ddlon/image",{
          method: 'POST',
          headers: myHeaders,
          body: formdata,
      });
      //回傳的值做text分析
      const data = await response.text();
      links.push(data);
    }
    return links;
  }
  