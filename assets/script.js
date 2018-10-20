function ready(fn) {
    if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

function toDataURL(el, callback, outputFormat) {
    var img = new Image();
    img.crossOrigin = 'Anonymous';
    img.onload = function () {
        var canvas = document.createElement('CANVAS');
        var ctx = canvas.getContext('2d');
        var dataURL;
        canvas.height = this.naturalHeight;
        canvas.width = this.naturalWidth;
        ctx.drawImage(this, 0, 0);
        dataURL = canvas.toDataURL(outputFormat).replace('data:image/png;base64,', '');
        callback(el, dataURL);
    };
    img.src = el.src;
    if (img.complete || img.complete === undefined) {
        img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
        img.src = el.src;
    }
}

function insertCaptionAfter(caption, referenceNode) {
    var newNode = document.createElement("div");
    newNode.style.position = "relative";
    newNode.style.top = "-10px";
    newNode.style.fontSize = "0.8em";
    newNode.style.fontStyle = "oblique";
    var newContent = document.createTextNode(caption);
    newNode.appendChild(newContent);
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}

function getClassification(el, content, type) {

    var source = `"source":{
        "imageUri":
          "${content}"
      }`

    var request = new XMLHttpRequest();

    request.open('POST', '/wp-json/vision/v1/images:annotate', true);
    request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    request.setRequestHeader('X-WP-Nonce', imageAutoLabels.nonce);

    if (type === 'base64') {
        source = `"content":"${content}"`
    }

    var data = `{
        "requests":[
          {
            "image":{
                ${source}
            },
            "features":[
              {
                "type":"LABEL_DETECTION",
                "maxResults":3
              }
            ]
          }
        ]
      }
      `

    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            var resp = request.responseText;
            var annotations = JSON.parse(resp);
            var labels = [];

            annotations.responses[0].labelAnnotations.forEach(function (item) {
                labels.push(item.description);
            });
            insertCaptionAfter(labels.join(' • '), el)
        }
    };
    request.send(data);
}

ready(function () {
    var images = document.querySelector('.post').querySelectorAll('img');
    images.forEach(function (item, i) {
        // If image is local then encode it.
        if (item.src.includes(window.location.hostname)) {
            toDataURL(
                item,
                function (el, dataUrl) {
                    getClassification(el, dataUrl, 'base64')
                }
            )
        } else {
            getClassification(item, item.src, 'url')
        }
    });
})