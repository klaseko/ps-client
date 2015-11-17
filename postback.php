<?php
  # display $_POST in PHP's built in web server
  # Array(
  #   [ref_no]    => YKFID3
  #   [token]     => SCm1M9XaTwwyve3zDrWhv0BMtIM
  #   [signature] => $2y$10$1ethNPkv7zs9qtwCTdofyeAtMpqXqo9sgPF3/Ok4yBV4d/J3duu9a
  #   [status]    => PAID
  # )
  # NOTE: generation of signature in the postback button on the dev server is not
  #       yet supported. so when you use the button for postbacks, no signature is
  #       included in the parameters.
  error_log(print_r($_POST, true));