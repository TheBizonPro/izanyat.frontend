class PermissionTranslate {
    constructor(permissionArray) {
        this.permissionArray = permissionArray;
    }

    translate() {
        let permissionArray = [];
        for (let permission of this.permissionArray) {
            permission = this.transalteElement(permission);
            permissionArray.push(permission);
        }
        return permissionArray;
    }

    transalteElement(permission) {
        switch (permission?.name || permission) {
            case "company.tasks.show": {
                return {
                    name: "company.tasks.show",
                    display_name: "Просмотр задач",
                };
            }
            case "company.tasks.delete": {
                return {
                    name: "company.tasks.delete",
                    display_name: "Удаление задач",
                };
            }
            case "company.tasks.update": {
                return {
                    name: "company.tasks.update",
                    display_name: "Обновление задач",
                };
            }
            case "company.tasks.contractor_assign": {
                return {
                    name: "company.tasks.contractor_assign",
                    display_name: "Назначение исполнителей",
                };
            }
            case "company.tasks.accept_job": {
                return {
                    name: "company.tasks.accept_job",
                    display_name: "Принятие работ",
                };
            }
            case "company.tasks.pay": {
                return {
                    name: "company.tasks.pay",
                    display_name: "Оплата работ",
                };
            }
            case "company.payouts.show": {
                return {
                    name: "company.payouts.show",
                    display_name: "Просмотр платежей",
                };
            }
            case "company.payouts.repay": {
                return {
                    name: "company.payouts.repay",
                    display_name: "Повторная оплата",
                };
            }
            case "company.documents.show": {
                return {
                    name: "company.documents.show",
                    display_name: "Просмотр документов",
                };
            }
            case "company.documents.create": {
                return {
                    name: "company.documents.create",
                    display_name: "Создание документов",
                };
            }
            case "company.documents.request_sign": {
                return {
                    name: "company.documents.request_sign",
                    display_name: "Запрос подписи",
                };
            }
            case "company.bank_account.update": {
                return {
                    name: "company.bank_account.update",
                    display_name: "Изменение платежных данных",
                };
            }
            case "company.company_data.update": {
                return {
                    name: "company.company_data.update",
                    display_name: "Изменение данных компании",
                };
            }
            case "company.receipts.show": {
                return {
                    name: "company.receipts.show",
                    display_name: "Просмотр чеков",
                };
            }
            case "company.projects.show": {
                return {
                    name: "company.projects.show",
                    display_name: "Просмотр проектов",
                };
            }
            case "company.projects.create": {
                return {
                    name: "company.projects.create",
                    display_name: "Создание проектов",
                };
            }

            default:
                break;
        }
    }
}

export default PermissionTranslate;
