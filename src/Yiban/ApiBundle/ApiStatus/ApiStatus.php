<?php
namespace Yiban\ApiBundle\ApiStatus;

class ApiStatus
{
    #成功
    const API_SUCCESS = 200;
    
    #失败
    #用户相关
    
    #app相关
    const APP_NOT_EXSIT = 10001;
    
    #message相关
    const MESSAGE_PUSH_FAIL = 20001;
    
    const MESSAGE_INVALID = 20002;
    
    
    #client相关
    const CLIENT_INVALID = 30001;
    const CLIENT_SAVE_FAIL = 30002;
    
    
}
