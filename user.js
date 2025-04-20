export default class User {
    constructor(userID){
        this.userID = userID;
        this.allUsers = [];
        this.userVariables = [];
        this.getAllUsersUrl = "/Scriptz/Backend/registration.php";
    }
    async getAllUsers(url = this.getAllUsersUrl){
        try {
            const response = await fetch(url);
            if(!response.ok){
                throw new Error('Failed to get all users');
            } else {
                return this.allUsers = response.json();
            }
        } catch(error){
            console.error('Failed to fetch all users');
            return [];
        }
    }
    async getUserVariables(userID, url = this.getAllUsersUrl){
        try {
            const result = await this.getAllUsers(url);
            if(result.status === "success"){
                return this.userVariables = result.find(user => user.RegID === userID);
            } else {
                throw new Error('Failed to get user variables');
            }
        } catch(error){
            console.error('Failed to get user variables');
            return [];
        }
    }
}