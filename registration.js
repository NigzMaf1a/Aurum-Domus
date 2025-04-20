export default class Registration {
    constructor(regInfo){
        //regInfo is an array of data input into the registration form
        if(regInfo.length !== 9) throw new Error('Not enough parameters for registration');
        this.dataForRegistration = {
            Name1 : regInfo[0],
            Name2 : regInfo[1],
            PhoneNo : regInfo[2],
            Email : regInfo[3],
            Password : regInfo[4],
            Gender : regInfo[5],
            RegType : regInfo[6],
            Location : regInfo[7],
            accStatus : regInfo[8]
        }; 
        this.regEndpointUrl = "/Scriptz/Backend/registration.php"
    }
    async registerUser(userDataObject = this.dataForRegistration, url = this.regEndpointUrl){
        try {
            const response = await fetch(url, userDataObject);
            if(!response.ok){
                throw new Error('Failed to fetch register user endpoint');
            }
        } catch(error) {
            console.error('Error registering new user');
            return [];
        } 
    }
}