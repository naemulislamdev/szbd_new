const handleMetaImgChange = function () {
    var e = document.querySelector("#meta_img").files;
    0 !== e.length && ((e = e[0]), readFile1(e));
  },
  readFile1 = function (e) {
    if (e) {
      const n = new FileReader();
      ((n.onload = function () {
        document.querySelector(".meta_img_preview_box").innerHTML =
          `<img class="preview-content" src=${n.result} />`;
      }),
        n.readAsDataURL(e));
    }
  };
const handleSizeChartChange = function () {
    var e = document.querySelector("#size_chart").files;
    0 !== e.length && ((e = e[0]), readFile2(e));
  },
  readFile2 = function (e) {
    if (e) {
      const n = new FileReader();
      ((n.onload = function () {
        document.querySelector(".size_chart_box").innerHTML =
          `<img class="preview-content" src=${n.result} />`;
      }),
        n.readAsDataURL(e));
    }
  };
const handleThumbnailChange = function () {
    var e = document.querySelector("#thubnail").files;
    0 !== e.length && ((e = e[0]), readFile3(e));
  },
  readFile3 = function (e) {
    if (e) {
      const n = new FileReader();
      ((n.onload = function () {
        document.querySelector(".thumbnail_preview_box").innerHTML =
          `<img class="preview-content" src=${n.result} />`;
      }),
        n.readAsDataURL(e));
    }
  };

var uppy1 = new Uppy.Uppy().use(Uppy.Dashboard, {
  inline: !0,
  target: "#product_img",
});

uppy1.on("complete", (e) => {
  console.log(e);
});
