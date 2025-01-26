 ```mermaid
classDiagram
    class User {
        - role
        - name
        - email
        - password
    }
    class PaykeUser {
        - status
        - tag_id
        - payke_host_id
        - payke_db_id
        - payke_resource_id
        - user_folder_id
        - user_app_name
        - app_url
        - enable_affiliate
        - user_name
        - email_address
        - superadmin_username
        - superadmin_password
        - memo
        - payke_order_id
    }
    class PaykeHost {
        - status
        - name
        - hostname
        - remote_user
        - port
        - identity_file
        - resource_dir
        - public_html_dir
    }
    class PaykeDb {
        - status
        - payke_host_id
        - db_host
        - db_username
        - db_password
        - db_database
    }
    class PaykeResource {
        - version
        - version_x
        - version_y
        - version_z
        - payke_name
        - payke_zip_name
        - payke_zip_file_path
        - memo
    }
    class PaykeUserTag {
        - name
        - color
        - order_no
        - is_hidden
    }
    class ReleaseNote {
        - version
        - title
        - background
        - content
        - created_at
    }
    class DeployLog {
        - type
        - user_id
        - user_name
        - user_app_name
        - title
        - message
        - deploy_params
        - deployer_log
        - memo
    }
    class DeploySetting {
        - key
        - value
        - no
    }
    class DeploySettingUnit {
    }

    User o-- PaykeUser
    PaykeUserTag o-- PaykeUser
    PaykeUser o-- PaykeHost
    PaykeUser o-- PaykeDb
    PaykeHost o-- PaykeDb
    PaykeUser o-- PaykeResource
    DeploySettingUnit o-- DeploySetting
```