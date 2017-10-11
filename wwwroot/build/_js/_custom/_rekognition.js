var CognitoIdentity = require('aws-sdk/clients/cognitoidentity');
var Rekognition = require('aws-sdk/clients/rekognition');

var rekognition;
var region = 'us-east-1';

var ciObject = new CognitoIdentity({
    region: region
});
ciObject.getId({
    IdentityPoolId: 'us-east-1:0eb3f69d-9364-488e-bd31-68553fd3277b'
}, function(err, identityData) {
    if (err) console.log(err, err.stack); // an error occurred
    else {
        ciObject.getCredentialsForIdentity({
            IdentityId:  identityData.IdentityId
        }, function(err, credentialsData) {
            if (err) console.log(err, err.stack); // an error occurred
            else {
                var myCredentials = credentialsData.Credentials;
                var rekognitionParams = {
                    apiVersion: '2016-06-27',
                    accessKeyId: myCredentials.AccessKeyId,
                    secretAccessKey: myCredentials.SecretKey,
                    sessionToken: myCredentials.SessionToken,
                    region: region
                };
                rekognition = new Rekognition(rekognitionParams);
            }
        });
    }
})

// AWS.config.update({
//     credentials: myCredentials,
//     region: 'us-east-1'
// });



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