export default class Login{
    constructor(email, password){
        this.email = email;
        this.password = password;
        this.loginEndpointUrl = "/Scriptz/Backend/login.php"; //Add endpoint
    }
    async loginUser(email, password, url = this.loginEndpointUrl){
        try {
            const response = await fetch(url, {email, password});
            if(!response.ok) {
                throw new Error('Failed to login user');
            } else {
                const regType = await this.getRegType(email);
                switch (regType) {
                    case (regType === "Manager"):
                        window.location.href = "/Pages/managerDashboard.php"; 
                        break;
                    case (regType === "Customer"):
                        window.location.href = "/Pages/customerDashboard.php";
                        break;
                    case (regType === "Chef"):
                        window.location.href = "/Pages/chefDashboard.php";
                        break;
                    case (regType === "Waiter"):
                        window.location.href = "/Pages/waiterDashboard.php";
                        break;
                    case (regType === "Janitor"):
                        window.location.href = "/Pages/janitorDashboard.php";
                        break;
                    default:
                        window.location.reload();
                }
            }
        } catch(error){
            console.error('Failed to login user');
            return [];
        }
    }
    async getRegType(email){
        try {
            const response = await fetch("/Scriptz/Backend/user.php"); //Add endpoint
            if(!response.ok){
                throw new Error('Failed to fetch user info');
            } else{
                const data = await response.json();
                const thisUser = data.find(user => user.Email === email);
                const regType = thisUser.RegType;
                return regType;
            }
        } catch(error){
            console.error('Failed to get user registration type');
            return null;
        }
    }
    async getAccStatus(email){
        try {
            const response = await fetch("/Scriptz/Backend/user.php"); //Add endpoint
            if(!response.ok){
                throw new Error('Failed to fetch user info');
            } else{
                const data = await response.json();
                const thisUser = data.find(user => user.Email === email);
                const accStatus = thisUser.accStatus;
                return accStatus;
            }
        } catch(error){
            console.error('Failed to get user account status');
            return null;
        }
    }
}