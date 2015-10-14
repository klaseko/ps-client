class PaymentsController < ApplicationController
  require 'digest'
  require 'bcrypt'

  def new
    @payment = Payment.new
    @payment.client_key = Rails.application.config.client_key
    @payment.ref_no = SecureRandom.hex(8)
    @payment.currency = "PHP"
  end

  def post
    # parsed_params = JSON.parse(payment_params.to_s)
    @payment = Payment.create payment_params

    if @payment.save!
      response = get_transaction_token

      if response['error']
        render json: response
      elsif response['token']
        redirect_to Rails.application.config.ps_url + "/payment?t=#{response['token']}"
      end

    end
  end

  def callback
    if @payment = Payment.find_by_ref_no(params['ref_no'])
      render :success
    end

  end

  private
    def payment_params
      params.require(:payment).permit!
    end

    def get_transaction_token
      access_token_request =
        http_adapter.get do |req|
          req.url                        '/oauth2/token'
          req.headers['Client-Key']    = @payment.client_key.to_s
          req.headers['Client-Secret'] = Rails.application.config.client_secret
        end
      @access_token = JSON.parse(access_token_request.body)['access_token']
      @payment = @payment.attributes
      valid_transaction_string =
        @payment["title"] +
        @payment["email"] +
        @payment["currency"] +
        @payment["total"].to_s +
        @payment["description"] +
        @payment["ref_no"] +
        @payment["email"] +
        @payment["mobile_no"] +
        @payment["client_tracking_id"] +
        Rails.application.config.client_secret

      signature = Digest::SHA2.new(256)
      signature << valid_transaction_string
      @payment["signature"] = BCrypt::Password.create(signature)
      @payment["signature_string"] = valid_transaction_string
      transaction_post_request =
        http_adapter.post do |req|
          req.url                        '/payment'
          req.headers['Content-Type']  = 'application/json'
          req.headers['Authorization'] = "Bearer #{@access_token}"
          req.headers['Client-Key']    = Rails.application.config.client_key
          req.body                     = @payment.to_json
        end
      JSON.parse(transaction_post_request.body)
    end

    def http_adapter
      conn = Faraday.new(:url => Rails.application.config.ps_url) do |faraday|
        faraday.request  :url_encoded
        faraday.response :logger
        faraday.adapter  Faraday.default_adapter
      end
    end
end
