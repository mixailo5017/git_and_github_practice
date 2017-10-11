var AWS = require('aws-sdk');
var myCredentials = new AWS.CognitoIdentityCredentials({
    IdentityPoolId: 'us-east-1:0eb3f69d-9364-488e-bd31-68553fd3277b'
});
AWS.config.update({
    credentials: myCredentials,
    region: 'us-east-1'
});

var rekognition = new AWS.Rekognition({
    apiVersion: '2016-06-27'
});

function detectFaceFromBlob(imageBlob) {
    var params = {
        Image: {
            Bytes: imageBlob
        }
    };

    return new Promise((resolve, reject) => {
        rekognition.detectFaces(params, function(err, data) {
            if (err) {
                reject(err);
            } else {
                var foundFace = data.FaceDetails.length > 0;
                resolve(foundFace);
            }
        });
    });
}

module.exports = {
    detectFaceFromBlob: detectFaceFromBlob
}