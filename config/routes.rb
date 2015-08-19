Rails.application.routes.draw do
  root 'application#root'

  get 'payments/new' => 'payments#new'

  post 'payments/post' => 'payments#post'

  get 'callback_url' => 'payments#callback'
end
