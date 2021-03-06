function newNotification(ntf_id, sender, action, receiver_id, target, target_id, receiver, datetime){
    var self = this;

    self.ntf_id = ntf_id;
    self.sender = sender;
    self.action = action;
    self.receiver_id = receiver_id;
    self.target = target;
    self.target_id = target_id;
    self.receiver = receiver;
    self.datetime = datetime;

    if(self.target_id!=0)
        self.msg = sender + " " + action + " " + target;
    else
        self.msg = sender + " " + action;
    self.seeAllMsg = " "+action + " " + target + "    " + datetime;
}

function NotificationViewModel() {
    var self = this;
    self.ntfs = ko.observableArray();
    self.ntf_id = ko.observable();
    self.ntfsNUM = ko.observable();
    self.visibleNTF = ko.observable(false);
    
    self.displayMore = function(){
        self.visibleNTF(!self.visibleNTF());
    }

    $.ajax({
        url: my_pligg_base+'/notification/notificationController.php?action=allUserNTF', // /Colfusion
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.notifications!=null){
                for (var i = data.notifications.length-1; i>=0; i--) {
                    var tmpntf = new newNotification(
                        data.notifications[i].ntf_id,
                        data.notifications[i].sender,
                        data.notifications[i].action,
                        data.notifications[i].receiver_id,
                        data.notifications[i].target,
                        data.notifications[i].target_id, 
                        " ", " ");
                    self.ntfs.push(tmpntf);
                }
            }//end if
            self.ntfs.push(new newNotification("all ntf","******","See All","all receiver","******", "all", data.receiver, " "));
        }
    });

    self.goToStory = function(ntf) {
        if (ntf.target_id == "all"){
            story_url = my_pligg_base+"/user.php?login="+ntf.receiver+"&view=notification"; //../Colfusion
            window.open(story_url);
        }
        else if (ntf.target_id == "no"){
            //no nothing
        }
        else {
            $.ajax({
                url: my_pligg_base+'/notification/notificationController.php?action=removeNTF&ntf_id='+ntf.ntf_id,//../Colfusion
                type: 'post',
                dataType: 'json'
            });
            if(ntf.target_id!=0)
                story_url = my_pligg_base+"/story.php?title="+ntf.target_id;///Colfusion
            else
                story_url = my_pligg_base+"/user.php?login="+ntf.sender;///Colfusion
            window.open(story_url);
        }
    }//end of goToStory

}

function seeAllViewModel(){
    var self = this;
    self.ntfs = ko.observableArray();
    self.ntf_id = ko.observable();
    self.ntfsNUM = ko.observable();
    self.visibleNTF = ko.observable(false);
    
    self.displayMore = function(){
        self.visibleNTF(!self.visibleNTF());
    }

    $.ajax({
        url: my_pligg_base+'/notification/notificationController.php?action=seeAll', ///Colfusion
        type: 'get',
        dataType: 'json',
        success: function (data) {
            if (data.notifications!=null){
                for (var i = data.notifications.length-1; i>=0; i--) {
                    var tmpntf = new newNotification(
                        " ",
                        data.notifications[i].sender,
                        data.notifications[i].action,
                        " ",
                        data.notifications[i].target,
                        data.notifications[i].target_id, 
                        " ",
                        data.notifications[i].datetime);
                    self.ntfs.push(tmpntf);
                }
            }//end if
        }
    });

    self.goToStory = function(ntf) {
        story_url = my_pligg_base+"/story.php?title="+ntf.target_id;///Colfusion
        window.open(story_url);
    }//end of goToStory

    self.goToUser = function(ntf){
        user_url = my_pligg_base+"/user.php?login="+ntf.sender;//../Colfusion
        window.open(user_url);
    }//end of goToUser
}

function GetCurrentNTFNum(){
    var number = 0;
    $.ajax({
        url: my_pligg_base+'/notification/notificationController.php?action=getNTFnum',//../Colfusion
        type: 'get',
        dataType: 'json',
        success: function (data) {
            number = data[0].total;
            if (number != 0){
                document.getElementById("notification_icon").innerHTML += "("+number+")";
                document.getElementById("ntfSound").play();
            }
        }
    });
}

ko.bindingHandlers.ntfVisible = {
    init: function(element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        $(element).toggle(ko.utils.unwrapObservable(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function(element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.utils.unwrapObservable(value) ? $(element).fadeIn() : $(element).fadeOut();
    }
};

function applyNTF() {
    ko.applyBindings(new NotificationViewModel());
}

function applyBseeall() {
    ko.applyBindings(new seeAllViewModel());
}

